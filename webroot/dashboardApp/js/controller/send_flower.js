dashboardApp.controller('SendFlowerController', function($window, $scope, $location, $http, Patient, Vendor){
		
		$scope.pdata = Patient.data;
		$scope.vendor = Vendor.data;
		$scope.flowerImgSrc = false;
		$scope.flowers = {};
		$scope.flowers.category = "";
		$scope.flowers.subCategory = "";
		$scope.flowers.selectedProduct = "";
		$scope.sendFlowersRequest = {};
		$scope.availableDates = [];
		$scope.productList = [];
		$scope.submitBtn = true;
		$scope.productCount = null;
		$scope.wait = 0;
		
		$scope.sendFlowersRequest.patient_peoplehub_identifier = $scope.pdata.id; 
		$scope.sendFlowersRequest.name = $scope.pdata.name;
		
		getCategories();

		if(typeof $scope.vendor.vendor_florist_setting != 'undefined' && $scope.vendor.vendor_florist_setting != ""){
			$scope.flowerImgSrc = angular.copy($scope.vendor.vendor_florist_setting.product_image_url);
			$scope.sendFlowersRequest.image_url = $scope.flowerImgSrc;
			$scope.sendFlowersRequest.price = angular.copy($scope.vendor.vendor_florist_setting.price);
			$scope.sendFlowersRequest.product_code = angular.copy($scope.vendor.vendor_florist_setting.product_code);
			$scope.sendFlowersRequest.message = angular.copy($scope.vendor.vendor_florist_setting.message);
		}


			$http.get($window.host+'api/FloristOne/checkDeliveryDate/'+$scope.pdata.id).then(function(response){
				$scope.availableDates = response.data.deliveryDate.DATES;
				
				if(typeof(response.data.deliveryDate.errors) != "undefined"){
					swal("An error occured!" , response.data.deliveryDate.errors, "error");
				}
			},function(response){
				console.log('error in delivery date');
			});
		
		function getCategories(){

			$http.get($window.host+'api/FloristOne/getProductCategories').then(function(response){
				$scope.flowers.categories = response.data.product;
				if(typeof $scope.flowers.categories.All != "undefined"){
					delete $scope.flowers.categories.All;
				}
				if(typeof(response.data.product.errors) != "undefined"){
					swal("An error occured!" , response.data.product.errors, "error");
				}
			},function(response){
				swal("An error occured!" , response.data.message, "error");
			});

		}

		$scope.getProducts = function(){

			$scope.wait = 1;
			if($scope.flowers.subCategory != ""){
				$http.get($window.host+'api/FloristOne/getProductList/'+$scope.flowers.subCategory).then(function(response){
					$scope.productList = response.data.productList.PRODUCTS;
					$scope.productCount = response.data.productList.TOTAL;
					console.log($scope.productCount);
					$scope.wait = 0;
				},function(response){
					swal("An error occured!" , response.data.message, "error");
				});

			}
		}
 
		$scope.sendFlower = function(){
			$scope.submitBtn = false;
		console.log($scope.sendFlowersRequest);
		$http.post($window.host+'api/FloristOne/addFloristOrders', $scope.sendFlowersRequest).then(function(response){

			swal({
                title: "Saved",
                text: "Flower for this patient has been queued for approval",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#A7D5EA",
                confirmButtonText: "Okay",
                closeOnConfirm: true
            }, function () {
				$location.path('/patient');
				$scope.$apply();
            });
			$scope.submitBtn = true;

		}, function(response){
			
			swal({
				title: "Oops! Something didn't go well!",
				text: response.data.message,
				type: "warning",
			});
			console.log('In error case for save flower.');
			console.log(response);
			$scope.submitBtn = true;
			
		});
			console.log($scope.sendFlowersRequest);
		}

		$scope.selectProduct = function(media){
			$scope.sendFlowersRequest.price = media.PRICE;
			$scope.sendFlowersRequest.image_url = media.SMALL;
			$scope.flowerImgSrc = media.SMALL;
			$scope.sendFlowersRequest.product_code = media.CODE;
			console.log($scope.sendFlowersRequest);
		}
		
		
});