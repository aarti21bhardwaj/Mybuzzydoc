var tierPerks = angular.module('tierPerks', []);

tierPerks.controller('TierPerksController', function($scope, $http, $window, $filter, $element){
    
    var config = {  
                    headers:  {
                        'accept': 'application/json',
                    }
    };

    $scope.vendorId = $window.vendorId;
    $scope.request = {};
    $scope.tierPerkId = $window.tierPerkId;
    $scope.noTiers = false;
    $scope.tierUrl = $window.host + 'tiers/add';
    $scope.saveButton = false;
    
    if($scope.vendorId == 1){

        $scope.vendorDropDown = false;
        //get list of Vendors
        getVendors();

    }else{

        //get Vendor Tiers
        $scope.vendorDropDown = $scope.vendorId;
        getTiers($scope.vendorDropDown);
    }
    
    if(typeof $scope.tierPerkId != "undefined" && $scope.tierPerkId != ""){

        getTierPerk();
    
    }else{

        $scope.tierPerkId = "";

    }
    
    console.log($scope.tierPerkId );

    this.tiers = function(vendorId){
        console.log('here');
        getTiers(vendorId);
        if(typeof $scope.tierPerkId == "undefined" || $scope.tierPerkId == ""){
            $scope.request.tier_id = "";
        }

    }

    function getTierPerk(){

        $http.get($window.host + 'api/tier-perks/'+$scope.tierPerkId, config).then(function(response){
                
            console.log(response.data);
            $scope.request.tier_id = response.data.tierPerk.tier_id;
            $scope.request.perk = response.data.tierPerk.perk;
            $scope.vendorDropDown = response.data.tierPerk.tier.vendor_id;
            getTiers($scope.vendorDropDown);



        },function(response){

            console.log('error in getting tier perk');
            console.log(response);
        
        });
    }

    function getTiers(vendorId = null){

        $scope.vendorDropDown = vendorId;
        $http.get($window.host + 'api/vendors/tiers/'+vendorId, config).then(function(response){
                
            console.log(response.data);
            $scope.tiers = response.data;  
            $scope.noTiers = false;            

        },function(response){

            $scope.noTiers = true;
        
        });
    }

    $scope.savePerk = function(){
        
        var req = {
            
            method: $scope.tierPerkId.length > 0 ? 'PUT' : 'POST',
            url: $window.host + 'api/tier-perks/'+$scope.tierPerkId,
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
            $window.location.href = $window.host + 'tier-perks';
             

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