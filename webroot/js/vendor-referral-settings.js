var referralSettings = angular.module('referralSettings', []);

referralSettings.controller('ReferralSettingsController', function($scope, $http, $window, $filter, $element){
    
    var config = {  
                    headers:  {
                        'accept': 'application/json',
                    }
    };


	$scope.vendorId = $window.vendorId;
    $scope.request = {};
    $scope.giftCoupons = false;
    $scope.vendorReferralSettingId = $window.vendorReferralSettingId;
	$scope.giftCouponUrl = $window.host + 'GiftCoupons/add';
	if($scope.vendorId == 1){

        $scope.showVendors = true;
        //get list of Vendors
        getVendors();

    }else{

        $scope.showVendors = false;
        $scope.request.vendor_id =  $window.vendorId;
        getVendorGiftCoupons();

    }

    if(typeof $scope.vendorReferralSettingId != "undefined" && $scope.vendorReferralSettingId != ""){

    	getVendorReferralSetting()
    
    }else{

    	$scope.vendorReferralSettingId = false;

    }

    this.submitForm = function(){

    	var req = {
            
            method: $scope.vendorReferralSettingId == false ? 'POST' : 'PUT',
            url: $window.host + 'api/vendor-referral-settings/'+ (!$scope.vendorReferralSettingId ? "" : $scope.vendorReferralSettingId) ,
            data: $scope.request,
            config: config
        }

        $http(req).then(function(response){
                
            console.log(response);
            swal({
                        title: "Saved",
                        text: response.data.message,
                        type: "success",
                        showConfirmButton: false,
                    });

            if(typeof response.data.message != 'undefined')
            	$window.location.href = $window.host + 'vendor-referral-settings';
                    

        },function(response){

           swal("Error", response.data.message, "error");
           console.log("error in saving referral setting");
           console.log(response);
        
        });

    }

    this.vendorChange = function(){

    	$scope.giftCoupons = false;
    	getVendorGiftCoupons();
    }

    this.refresh = function(){

    	getVendorGiftCoupons();
    }

    function getVendorReferralSetting(){

        $http.get($window.host + 'api/VendorReferralSettings/'+$scope.vendorReferralSettingId, config).then(function(response){
                
            console.log(response.data);
            setting = response.data.vendorReferralSetting;
            $scope.request.id = setting.id;
            $scope.request.referral_level_name = setting.referral_level_name;
            $scope.request.referrer_award_points = setting.referrer_award_points;
            $scope.request.referree_award_points = setting.referree_award_points;
            $scope.request.vendor_id = setting.vendor_id;
            if(typeof setting.referral_setting_gift_coupon != 'undefined' && setting.referral_setting_gift_coupon != null){

            	$scope.request.referral_setting_gift_coupon = {};
            	$scope.request.referral_setting_gift_coupon.vendor_referral_setting_id = setting.id;
            	$scope.request.referral_setting_gift_coupon.gift_coupon_id = setting.referral_setting_gift_coupon.gift_coupon_id;
            	
            }
            getVendorGiftCoupons();

        },function(response){

            console.log('error in getting vendor referral setting');
            console.log(response);
        
        });
    }

    function getVendorGiftCoupons(){
    	// console.log();
        $http.get($window.host + 'api/GiftCoupons/getVendorsCoupons/1/'+$scope.request.vendor_id, config).then(function(response){
             

            if(response.data.response.giftCoupons != [])
            	$scope.giftCoupons = response.data.response.giftCoupons;
            else
            	$scope.giftCoupons = false;
            console.log(response.data);

        },function(response){

            swal("Error!",response.data.message, "error");
        
        });
    }

    function getVendors(){

        $http.get($window.host + 'api/vendors/', config).then(function(response){
             
            $scope.vendors = response.data.vendors;   
            console.log(response.data);

        },function(response){

            swal("Error!",response.data.message, "error");
        
        });
    }

});