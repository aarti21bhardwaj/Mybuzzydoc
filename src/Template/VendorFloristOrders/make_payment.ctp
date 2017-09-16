<?php if(!$floristOrder){ ?>
    <div class="middle-box text-center animated fadeInDown">
        <h2>Oops!</h2>

        <h3 class="font-bold">This page has <em>expired</em>.<br>
        If you are yet to approve the order, log in to your account.</h3><br/>
    </div>

<?php }else{ ?>
    
    <script src="https://js.stripe.com/v3/"></script>

    <div class="row" id="flowerApp" ng-app="flowerOrderApproval" ng-controller="FlowerOrderApprovalController as Orders" ng-cloak>
        <div class="ibox-content" ng-show="wait">
            <div class="spiner-example">
                <div class="sk-spinner sk-spinner-cube-grid">
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                    <div class="sk-cube"></div>
                </div>
            </div>
        </div>
        <div class="tab-content" ng-hide="wait || selectedOrder[0].status || congratulationsMessage">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">

                        <div class="ibox-content">
                            <h2 class="text-center">Make Payment</h2>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 payments-method" ng-if="allCards.length > 0" ng-show="!newCardTab">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <span class="pull-right"><button class="btn btn-primary" style="margin-top: -4px;" type="button" ng-if="allCards.length > 0" ng-click="cardsSlidechange(1)">Add new card</button>
                                            </span>
                                            <h5 class="panel-title">
                                                Select a card from the list below 
                                            </h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">Last 4 digits</th>
                                                            <th class="text-center">Expiry</th>
                                                            <th class="text-center">Select</th>
                                                            <th class="text-center">Remove card</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat = "card in allCards">
                                                            <td class="text-center">
                                                                <i class="fa fa-cc-visa text-info" ng-if="card.BRAND == 'Visa'"></i>
                                                                <i class="fa fa-cc-amex text-success" ng-if="card.BRAND == 'American Express'"></i>
                                                                <i class="fa fa-cc-discover text-danger" ng-if="card.BRAND == 'Discover'"></i>
                                                                <i class="fa fa-cc-mastercard text-warning" ng-if="card.BRAND == 'MasterCard'"></i>
                                                            </td>
                                                            <td class="text-center">{{card.LAST4}}</td>
                                                            <td class="text-center">{{card.EXP_MONTH}}/{{card.EXP_YEAR}}</td>
                                                            <td class="text-center">
                                                                <input type="radio" name="cardRadio" ng-model="selectedCard.cardId" ng-value="card.ID">
                                                            </td>
                                                            <td class="text-center">
                                                                <button class="btn btn-danger btn-xs btn-outline" ng-click="removeCard(card.ID)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-primary" ng-disabled="selectedCard.cardId == ''" type="button" ng-click="placeOrder(selectedCard)">Place Order</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-offset-3 payments-method" ng-show="newCardTab">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="pull-left">
                                                <i class="fa fa-cc-amex text-success"></i>
                                                <i class="fa fa-cc-mastercard text-warning"></i>
                                                <i class="fa fa-cc-discover text-danger"></i>
                                                <i class="fa fa-cc-visa text-info"></i>
                                            </div>
                                            <div class="pull-right">
                                                <button class="btn btn-primary" style="margin-top: -4px;" type="button" ng-if="allCards.length > 0" ng-click="cardsSlidechange(0)">Saved cards 
                                                </button>
                                            </div>
                                            <h5 class="panel-title col-sm-offset-5">
                                                Add a new card
                                            </h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-10">
                                                <form action="/charge" method="post" id="payment-form">
                                                  <div class="form-row">
                                                    <label for="card-element">
                                                      Credit or debit card
                                                    </label>
                                                    <div id="card-element">
                                                      <!-- a Stripe Element will be inserted here. -->
                                                    </div>

                                                    <!-- Used to display form errors -->
                                                    <div id="card-errors"></div>
                                                    </div>
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-xs-12">
                                                            
                                                            <button class="btn btn-primary" type="submit" >Place Order</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle-box text-center animated fadeInDown" ng-show="selectedOrder[0].status || congratulationsMessage">
            <h2>Congrats!</h2>
            <br>
            <br>
            <h3 class="font-bold">Your order has been approved.<br></h3>
        </div>
    </div>


<?php    } ?> 


<!-- CSS for stripe elements from stripe documentation -->
<style type="text/css">
    .StripeElement {
      background-color: white;
      padding: 8px 12px;
      border-radius: 4px;
      border: 1px solid transparent;
      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
</style>
<!-- Angular app for flower order approval -->
<?= $this->Html->script(['flower-order-approval']) ?>

<script type="text/javascript">
// initialize js variables set in php
<?php 
    echo "var orderTotal = ".$orderTotal.";";
    echo "var selectedOrder = '[".json_encode($floristOrder)."]';";
    echo "var customerId = '".$customerId."';";
    echo "var vendorId = ".$vendorId.";";
?>
    

$(document).ready(function(){
    //setting status = 3 in flowerApp. For status=3, getOrders Api is not called.
    angular.element('#flowerApp').scope().status = 3;

    //Call method stripeKey() and set its value.
    angular.element('#flowerApp').scope().stripeKey();
    angular.element('#flowerApp').scope().$apply();

    //set the vendorId in flowerApp.
    angular.element('#flowerApp').scope().vendorId = vendorId;
    //Update the vendor's customer data.
    angular.element('#flowerApp').scope().vendor.customer_id = customerId;
    
    //Call getCards() to fetch all the cards that have been saved for the vendor.
    angular.element('#flowerApp').scope().getCards();
    angular.element('#flowerApp').scope().$apply();
    
    //Setting scope variables selectedOrders and ordertotal
    angular.element('#flowerApp').scope().selectedOrder = JSON.parse(selectedOrder);
    angular.element('#flowerApp').scope().orderTotal.ORDERTOTAL = orderTotal;
});

    //the method to generate stripe elements and attach event listeners(as on Stripe documnetation)
    function stripeInit(pubKey) {

        // Create a Stripe client
        var stripe = Stripe(pubKey);

        // Create an instance of Elements
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
          base: {
            color: '#32325d',
            lineHeight: '24px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };

        // Create an instance of the card Element
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
          var displayError = document.getElementById('card-errors');
          if (event.error) {
            displayError.textContent = event.error.message;
          } else {
            displayError.textContent = '';
          }
        });

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
          event.preventDefault();

          stripe.createToken(card).then(function(result) {
            if (result.error) {
              // Inform the user if there was an error
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;

              // angular.element('#flowerApp').scope().placeOrder(result);
              // angular.element('#flowerApp').scope().$apply();
            } else {
              // Send the token to your server
              //If the card has been saved successfully, call placeOrder().
              angular.element('#flowerApp').scope().placeOrder(result);
              angular.element('#flowerApp').scope().$apply();
            }
          });
        });
    }
</script>