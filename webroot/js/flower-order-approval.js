var flowerOrderApproval = angular.module('flowerOrderApproval', ["datatables"]);

flowerOrderApproval.controller('FlowerOrderApprovalController', function($scope, $http, $window){



	$scope.status = 2;
	$scope.orders = [];
	$scope.keysOfSelectedOrders = [];
	$scope.selectedOrder = [];
	$scope.slide = 0;
	$scope.wait=0;
    $scope.productsForTotal = [];
    $scope.orderTotal = [];
    // $scope.card = {};
    $scope.vendorId = '';
    $scope.disableGetTotal = 1;
    $scope.vendor = {};
    $scope.allCards = [];
    $scope.newCardTab = 1;
    $scope.selectedCard = {'cardId' : ''};
    $scope.congratulationsMessage = 0;
    $scope.selectAll = 0;

    $scope.toggleSelectAll = function(){
    	$scope.selectAll = !$scope.selectAll;
		for (x in $scope.keysOfSelectedOrders){
			$scope.keysOfSelectedOrders[x] = $scope.selectAll;
		}
		$scope.disabledGetTotal();	
    }


	$scope.$watch(function(){
        return $scope.status;
    }, function(newValue, oldValue) {
    	console.log('in watch');
    	console.log($scope.status);
    	//status = 3 is sent from makePayment page(one click flower order approval).
    	if(newValue !== 3){
    		$scope.getOrders();
    	}
    });

	$scope.disabledGetTotal = function(){
		
		var selectAll = 1;
		var oneSelected = false;
		for(x in $scope.keysOfSelectedOrders){
			if($scope.keysOfSelectedOrders[x]){
				oneSelected = true;
			}else{
				selectAll =  0;
			}
		}

		
		$scope.selectAll = selectAll;
		$scope.disableGetTotal = !oneSelected;
		console.log('selectAll '+ $scope.selectAll);
		console.log('disableGetTotal '+ $scope.disableGetTotal);
	}	

	$scope.cardsSlidechange = function(val){

		return $scope.newCardTab = val;
	}

	$scope.slideBack = function(){
		$scope.slide = $scope.slide-1;
	}

	$scope.getOrders = function(){

			$scope.keysOfSelectedOrders = [];

			if($scope.vendorId != null && typeof $scope.vendorId != 'undefined'){	
			$http.get($window.host + 'api/FloristOne/floristOrders/'+$scope.status+'/'+$scope.vendorId).then(function(response){
		                
		            $scope.orders = response.data.vendorFloristOrder;
		            console.log($scope.orders);
		        },function(response){

		            console.log(response);
		            console.log('error in getting flower orders');
		        });
			} else{
				$http.get($window.host + 'api/FloristOne/floristOrders/'+$scope.status).then(function(response){
		                
		            $scope.orders = response.data.vendorFloristOrder;
		            console.log($scope.orders);
		        },function(response){

		            console.log('error in getting flower orders');
		            console.log(response);
		        });
			}
	}


	$scope.getTotal = function(){

		$scope.wait=1; 

		$scope.productsForTotal = [];
		$scope.selectedOrder = [];
		for(x in $scope.keysOfSelectedOrders){
			
			var tempProducts = {};
			if($scope.orders[x] && $scope.keysOfSelectedOrders[x]){

				tempProducts['CODE'] = $scope.orders[x]['product_code'];
				tempProducts['PRICE'] = $scope.orders[x]['price'];
				tempProducts['RECIPIENT'] = { 'zipcode' : $scope.orders[x]['patient_address']['zipcode']};
				$scope.productsForTotal.push(tempProducts);
				$scope.selectedOrder.push($scope.orders[x]);
			}
		}

		if($scope.productsForTotal){

			if($scope.vendorId != null && typeof $scope.vendorId != 'undefined'){
			$http.post($window.host + 'api/FloristOne/getTotal/'+ $scope.vendorId, {'products' :$scope.productsForTotal}).then(function(response){

					if(typeof response.data.totalAmount.errors != 'undefined' ){
		            	
		            	swal({
				                title: "ERROR",
				                text: "Could not retrieve your order total." + response.data.totalAmount.errors,
				                type: "error",
				                showCancelButton: false,
				                confirmButtonColor: "#DD6B55",
				                confirmButtonText: "Okay!",
				                closeOnConfirm: true
				            });
		            }
		            $scope.orderTotal = response.data.totalAmount;
		            $scope.vendor = response.data.vendorDetails;

		            //$scope.wait will be given zero in stripkey function.
		            $scope.stripeKey();
		        },function(response){
		        	swal({
			                title: "ERROR!",
			                text: "Could not retrieve your order total." + response.data.message,
			                type: "error",
			                showCancelButton: false,
			                confirmButtonColor: "#DD6B55",
			                confirmButtonText: "Okay!",
			                closeOnConfirm: true
			            });

		            console.log('error in getting order total');
		            console.log(response);
		        });
			} else{
				$http.post($window.host + 'api/FloristOne/getTotal', {'products' :$scope.productsForTotal}).then(function(response){

					if(typeof response.data.totalAmount.errors != 'undefined' ){
		            	
		            	swal({
				                title: "ERROR",
				                text: "Could not retrieve your order total." + response.data.totalAmount.errors,
				                type: "error",
				                showCancelButton: false,
				                confirmButtonColor: "#DD6B55",
				                confirmButtonText: "Okay!",
				                closeOnConfirm: true
				            });
		            }
		            $scope.orderTotal = response.data.totalAmount;
		            $scope.vendor = response.data.vendorDetails;

		            //$scope.wait will be given zero in stripkey function.
		            $scope.stripeKey();
		        },function(response){
		        	swal({
			                title: "ERROR!",
			                text: "Could not retrieve your order total." + response.data.message,
			                type: "error",
			                showCancelButton: false,
			                confirmButtonColor: "#DD6B55",
			                confirmButtonText: "Okay!",
			                closeOnConfirm: true
			            });

		            console.log('error in getting order total');
		            console.log(response);
		        });
			}



		}
	}

	$scope.stripeKey = function(){

		$scope.wait=1;
		$http.get($window.host + 'api/FloristOne/getStripeKey/').then(function(response){
	                
	            stripeInit(response.data.response.STRIPE_PUBLISHABLE_KEY);
	            $scope.wait=0;
	            $scope.slide=$scope.slide+1;
	        },function(response){
	            console.log('error in getting stripeKey');
	            console.log(response);
	        });
	}

	$scope.getCards = function(){
		$scope.wait=1;

		$http.get($window.host + 'api/FloristOne/getCards/'+$scope.vendor.customer_id).then(function(response){
	            $scope.allCards = response.data.cardsData.CARDS;
	            if($scope.allCards.length > 0){
	            	$scope.newCardTab = 0;
	            }else{
	            	$scope.newCardTab = 1;
	            }
	            $scope.wait=0;
	            if($scope.slide != 2){
	            	$scope.slide=$scope.slide+1;
	            }
	        },function(response){

	            console.log('error in getting saved cards');
	            console.log(response);
	        });
	}

	$scope.removeCard = function(cardId){

		swal({
		        title: "Are you sure?",
		        text: "You really want to delete this card?",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Yes, delete it!",
		        closeOnConfirm: false
		    }, function () {

				$http.get($window.host + 'api/FloristOne/deleteCards/'+$scope.vendor.customer_id+'/'+cardId).then(function(response){
			    	
			    	if(response.data.response.errors.length == 0){
		        		swal("Deleted!", "Your card has been deleted.", "success");
		        		$scope.getCards();
			    	}

			        },function(response){
			            console.log('Error in deleting the card.');
			            console.log(response);
			        });
		        
		    });
	}

	$scope.placeOrder = function(stripeResult){
		$scope.wait = 1;

		if(stripeResult){
			
			var orderRequest = {};

			orderRequest.orders =  $scope.selectedOrder;
			
			if(typeof stripeResult.token != 'undefined') {
				orderRequest.cc = {'stripe_token' : stripeResult.token.id};
			} else {
				orderRequest.cc = {'card_id' : stripeResult.cardId};				
			}
			
			orderRequest.total = $scope.orderTotal.ORDERTOTAL;

			$http.post($window.host + 'api/FloristOne/placeOrder/'+ $scope.vendorId, orderRequest).then(function(response){

		            console.log(response);

		            //error return
		            if(typeof response.data.orderDetails.errors != 'undefined' ){
		            	
		            	swal({
				                title: "Payment Failed!",
				                text: "The payment failed and your order couldn't be placed. Reason being : "+response.data.orderDetails.errors,
				                type: "error",
				                showCancelButton: false,
				                confirmButtonColor: "#DD6B55",
				                confirmButtonText: "Okay!",
				                closeOnConfirm: true
				            });
		            	$scope.wait = 0;
		            }else{

		            	$scope.productsForTotal = [];
						$scope.selectedOrder = [];
						$scope.keysOfSelectedOrders = [];
						$scope.disabledGetTotal();
						$scope.selectedCard = {'cardId' : ''};
						$scope.wait = 0;
						$scope.congratulationsMessage = 1;
			            swal({
				                title: "Payment Successful",
				                text: "Orders placed successfully!",
				                type: "success",
				                showCancelButton: false,
				                confirmButtonColor: "#DD6B55",
				                confirmButtonText: "Okay!",
				                closeOnConfirm: true
				            }, function () {
				                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
					            $scope.status = 2;
					            $scope.getOrders();
					            $scope.slide=0;
					            $scope.$apply();
				            });
		            }
		        },function(response){

		        	swal({
			                title: "Payment Failed!",
			                text: "The payment failed and your order couldn't be placed. Internal server error.",
			                type: "error",
			                showCancelButton: false,
			                confirmButtonColor: "#DD6B55",
			                confirmButtonText: "Okay!",
			                closeOnConfirm: true
			            }, function () {
			                $scope.getOrders();
				            $scope.slide=0;

			            });
		            console.log('error in placing the order from buzzydoc.');
		            console.log(response);
		        });
		}else{

			swal({
	                title: "Check card details!",
	                text: stripeResult.error.message,
	                type: "error",
	                showCancelButton: false,
	                confirmButtonColor: "#DD6B55",
	                confirmButtonText: "Okay!",
	                closeOnConfirm: true
	            });
		}
	}

});