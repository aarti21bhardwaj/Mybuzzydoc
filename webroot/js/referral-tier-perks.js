var referralTierPerks = angular.module('referralTierPerks', []);

referralTierPerks.controller('ReferralTierPerksController', function($scope, $http, $window, $filter, $element){
    
    var config = {  
                    headers:  {
                        'accept': 'application/json',
                    }
    };

    $scope.vendorId = $window.vendorId;
    console.log($scope.vendorId);
    $scope.request = {};
    $scope.referralTierPerkId = $window.referralTierPerkId;
    $scope.noTiers = false;
    $scope.referralTierUrl = $window.host + 'referralTiers/add';
    $scope.saveButton = false;
    
    if($scope.vendorId == 1){

        $scope.vendorDropDown = false;
        //get list of Vendors
        getVendors();

    }else{
        console.log('getReferralTiers');
        //get Vendor Referral Tiers
        $scope.vendorDropDown = $scope.vendorId;
        getReferralTiers($scope.vendorDropDown);
    }
    
    if(typeof $scope.referralTierPerkId != "undefined" && $scope.referralTierPerkId != ""){

        getReferralTierPerk();
    
    }else{

        $scope.referralTierPerkId = "";

    }
    
    console.log($scope.referralTierPerkId );

    this.referralTiers = function(vendorId){
        console.log('here');
        getReferralTiers(vendorId);
        if(typeof $scope.referralTierPerkId == "undefined" || $scope.referralTierPerkId == ""){
            $scope.request.referral_tier_id = "";
        }

    }

    function getReferralTierPerk(){

        $http.get($window.host + 'api/referral-tier-perks/'+$scope.referralTierPerkId, config).then(function(response){
                
            console.log(response.data);
            $scope.request.referral_tier_id = response.data.referralTierPerk.referral_tier_id;
            $scope.request.perk = response.data.referralTierPerk.perk;
            $scope.vendorDropDown = response.data.referralTierPerk.referral_tier.vendor_id;
            getReferralTiers($scope.vendorDropDown);



        },function(response){

            console.log('error in getting tier perk');
            console.log(response);
        
        });
    }

    function getReferralTiers(vendorId = null){

        $scope.vendorDropDown = vendorId;
        $http.get($window.host + 'api/vendors/referralTiers/'+vendorId, config).then(function(response){
                
            $scope.referralTiers = response.data;  
            $scope.noTiers = false;            

        },function(response){

            $scope.noTiers = true;
        
        });
    }

    $scope.savePerk = function(){
        
        var req = {
            
            method: $scope.referralTierPerkId.length > 0 ? 'PUT' : 'POST',
            url: $window.host + 'api/referral-tier-perks/'+$scope.referralTierPerkId,
            data: $scope.request,
            config: config
        }

        $scope.saveButton = true;

        $http(req).then(function(response){
                
            console.log(response.data);
            swal({
                        title: "Saved",
                        text: response.data.response.message,
                        type: "success",
                        showConfirmButton: false,
                    });
            $scope.saveButton = false;
            $window.location.href = $window.host + 'referral-tier-perks';
                    

        },function(response){

           $scope.saveButton = false;
           console.log("error in saving perk");
        
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