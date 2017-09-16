var vendorFloristSettings = angular.module('vendorFloristSettings', []);

vendorFloristSettings.controller('VendorFloristSettingsController', function($scope, $http, $window){
    
    var config = {  
                    headers:  {
                        'accept': 'application/json',
                    }
    };


    $scope.flowerImgSrc = false;
	$scope.flowers = {};
	$scope.flowers.selectedProduct = "";
	$scope.flowers.category = "";
	$scope.flowers.subCategory = "";
	$scope.states = "";
	$scope.statesUrl = $window.host+"json/us_states_titlecase.json";
	$scope.flowerImgSrc = "http://icons.iconarchive.com/icons/dryicons/aesthetica-2/128/image-add-icon.png";
	$scope.floristSetting = {};
	$scope.productList = [];
	$scope.productCount = null;
	$scope.saveBtn = true;
	$scope.wait = 0;

	getCategories();
	getSettings();
	
	function getSettings(){
		$http.get($window.host+'api/FloristOne/getDefaultFloristSettings').then(function(response){
			if(response.data.floristSettings){
				$scope.flowerImgSrc = response.data.floristSettings.product_image_url;
				$scope.floristSetting.message = response.data.floristSettings.message;;
				var json = response.data.floristSettings.address;
			    obj = JSON.parse(json);

				$scope.floristSetting.address1 = obj.address;
				$scope.floristSetting.city = obj.city;
				$scope.floristSetting.state = obj.state;
				$scope.floristSetting.country = obj.country;
				$scope.floristSetting.zipcode = obj.zipcode;
			}
			},function(response){
				swal("An error occured!" , response.data.message, "error");
			});

		}




	function getCategories(){

			$http.get($window.host+'api/FloristOne/getProductCategories').then(function(response){
				$scope.flowers.categories = response.data.product;
				if(typeof $scope.flowers.categories.All != "undefined"){
					delete $scope.flowers.categories.All;
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
			$scope.wait = 0;
			},function(response){
				swal("An error occured!" , response.data.message, "error");
			});


			}
		}


	$scope.saveSettings = function(){
		$scope.saveBtn = false;

		$http.post($window.host+'api/FloristOne/addDefaultFloristSettings', $scope.floristSetting).then(function(response){

			swal({
				title: "Saved",
				text: "Florist settings saved",
				type: "success",
				showCancelButton: false,
                confirmButtonColor: "#A7D5EA",
                confirmButtonText: "Okay",
                closeOnConfirm: true
            }, function () {
				
            });
			$scope.saveBtn = true;

		}, function(response){
			
			swal({
				title: "Oops! Something didn't go well!",
				text: response.data.message,
				type: "warning",
				showCancelButton: false,
                confirmButtonColor: "#A7D5EA",
                confirmButtonText: "Okay",
                closeOnConfirm: true
            }, function () {
				console.log('here');
				// $window.location.href=$window.host+"/users/dashboard#!/";
				// $scope.$apply();
            });

			console.log('In error case for save florist setting');
			$scope.saveBtn = true;
			
		});

	}

	$scope.selectProduct = function(media){
			$scope.floristSetting.price = media.PRICE;
			$scope.floristSetting.product_image_url = media.SMALL;
			$scope.flowerImgSrc = media.SMALL;
			$scope.floristSetting.product_code = media.CODE;
		}

	$http.get($scope.statesUrl).success(function(data){
   		$scope.states = data;
	});

	});