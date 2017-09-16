<div class="row">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
     	<div class="">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Your Cards
                </div>
                <div class="panel-body">
                <?= $this->Flash->render() ?>
				<?php if(isset($getProfile) && $getProfile['data']['totalCards'] !=0){ ?>
            	
            		<?php foreach ($cards as $card) { ?>
		                <div class="col-md-4">
        		            <div class="payment-card" style = "border-color: black; border-radius:20px">
                 			   	<?php if($card['card_type'] == 'MasterCard'){ 
                    					$cardTypeIcon = "fa-cc-mastercard";
                    				  }else if($card['card_type'] == 'Visa'){
                    					$cardTypeIcon = "fa-cc-visa";
                    				  }else{
                    					$cardTypeIcon = "fa-credit-card";
                    				} ?>
                        		<i class="fa <?= $cardTypeIcon ?> fa-4x text-success"></i>
                        		<h2>
                        			**** **** **** <?= substr($card['card_number'],4,4) ?>
                        		</h2>
                        		<div class="row">
                            		<div class="col-sm-6">
                                		<small>
                                    		<strong>Expiry date:</strong> 
                                    			<?= $card['expiration_date']?>
                                		</small>
                            		</div>
		                        </div>
        		            </div>
                		</div> 	
			            <?php } ?>
            		</div>
            	</div>
            </div>
        </div>
<?php } ?>


<!-- TODO: Find a way to be able to switch between live and test urls -->
<?php 
    if(isset($hostedProfilePage)): 
        if(isset($getProfile) && $getProfile['data']['totalCards'] != 0):

?>
    <form method="post" action="<?= $editCardUrl?>"> <input type="hidden" name="token" value="<?= $hostedProfilePage['data'] ?>"/> <input type="hidden" name="paymentProfileId" value="<?= $getProfile['data']['paymentProfileId']?>"/><input type="submit" value="Edit Payment Details" class="btn btn-primary col-sm-offset-5"></form>

<?php 
        else:

?> 

<form method="post" action="<?= $addCardUrl?>"> <input type="hidden" name="token" value="<?= $hostedProfilePage['data'] ?>"><input type="submit" value="Add New Payment Details" class="btn btn-primary col-sm-offset-5"></form>

<?php   
        endif;
    endif;
?> 
</div>
</div>

