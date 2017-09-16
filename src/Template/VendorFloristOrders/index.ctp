<?= $this->Html->script(['flower-order-approval']) ?>
<script src="https://js.stripe.com/v3/"></script>

<div class="row" id="flowerApp" ng-app="flowerOrderApproval" ng-controller="FlowerOrderApprovalController as Orders" ng-cloak>
    <div class="tab-content ">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins" ng-init="getOrders()">
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
                    <div class="ibox-content" ng-show="slide == 0 && !wait">
                        <div class="row">
                            <div class="col-md-10">
                                <h2 ng-if="status != 2">{{status ? "Approved" : "Pending"}} Orders</h2>
                                <h2 ng-if="status == 2">All Orders</h2>
                            </div>
                            <!-- <div class="col-md-2"> -->
                            <!-- </div> -->
                            <div class="col-md-2 text-center">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Status<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="status = 2">All</a></li>
                                    <li class="divider"></li>
                                    <li><a ng-click="status = 0">Pending</a></li>
                                    <li class="divider"></li>
                                    <li><a ng-click="status = 1">Approved</a></li>
                                </ul>
                                <button type="button" id="checkAll" class= "btn btn-md" ng-class="{'btn-success': !selectAll, 'btn-warning': selectAll}"  ng-click = "toggleSelectAll()">{{!selectAll ? 'Select All' : 'Unselect All'}}</button>
                            </div>
                        </div>
                        <div class="table-responsive" ng-if="orders.length != 0">
                            <table datatable = "ng" class="table table-bordered table-hover">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Staff name</th>
                                        <th class="text-center">Product Code</th>
                                        <th class="text-center">Product Image</th>
                                        <th class="text-center">Delivery Date</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Order for</th>
                                        <th class="text-center" ng-if="status == 1">Select</th>
                                        <th class="text-center" ng-if="status == 0">Select</th>
                                        <th class="text-center" ng-if="status == 2">Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat = "order in orders" ng-class="{'bg-success': order.status == 1 , 'bg-danger': order.status == 0}">
                                        <td class="text-center" >{{$index+1}}</td>
                                        <td class="text-center">{{order.user.first_name}}</td>
                                        <td class="text-center">{{order.product_code}}</td>
                                        <td class="text-center"><img style="height: 100px;" src='{{order.image_url}}'></td>
                                        <td class="text-center">{{order.delivery_date}}</td>
                                        <td class="text-center"> ${{order.price}}</td>
                                        <td class="text-center">{{order.status ? "Approved" : "Pending"}}</td>
                                        <td class="text-center"> {{order.name}}</td>
                                        <td class="text-center" ng-if="status == 1">{{order.vendor_florist_transactions[0].florist_transaction_id}}</td>
                                        <td class="text-center" ng-if="order.status == 0"><input type="checkbox" ng-click="disabledGetTotal()" ng-init="keysOfSelectedOrders[$index] = false" ng-model="keysOfSelectedOrders[$index]" name="flowers" ></td>
                                        <td class="text-center" ng-if="order.status == 1 && status == 2"><i class="fa fa-check" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center" ng-if="orders.length != 0">
                            <button class="btn btn-primary " ng-if="status == 0 || status == 2" type="button" ng-disabled="disableGetTotal" ng-click="getTotal()">View cart total</button>
                        </div>
                        <div class="text-center" ng-if="orders.length == 0">
                            <h3>There are no pending orders to approve.</h3>
                        </div>
                    </div>
                    <div class="ibox-content" ng-show="slide == 1 && !wait">
                        <h2 class="text-center">Order Summary</h2>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="table-responsive m-t">
                                    <table class="table invoice-table">
                                        <thead>
                                        <tr>
                                            <th>Item List</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat = "order in selectedOrder">
                                            <td><div><strong>Flower for {{order.name}}</strong></div>
                                                <small>Product Code: {{order.product_code}}.</small></td>
                                            <td>${{order.price}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <table class="table invoice-total">
                                    <tbody>
                                    <tr>
                                        <td><strong>Sub Total :</strong></td>
                                        <td>{{orderTotal['SUBTOTAL']}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Service Charge :</strong></td>
                                        <td>{{orderTotal['FLORISTONESERVICECHARGE']}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Taxes :</strong></td>
                                        <td>{{orderTotal['TAXTOTAL']}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TOTAL :</strong></td>
                                        <td><strong>${{orderTotal['ORDERTOTAL']}}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-warning" type="button" ng-click="slideBack()">Back</button>
                            <button class="btn btn-primary " type="button" ng-click="getCards();">Select payment method</button>
                        </div>
                    </div>
                    <div class="ibox-content" ng-show="slide == 2 && !wait">
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
                                                    <button class="btn btn-warning" ng-click="slideBack()">Back</button>
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
                                                        <button class="btn btn-warning" type="button" ng-click="slideBack()">Back</button>
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
</div>

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

<script type="text/javascript">

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
              angular.element('#flowerApp').scope().placeOrder(result);
              angular.element('#flowerApp').scope().$apply();
            }
          });
        });
    }
</script>