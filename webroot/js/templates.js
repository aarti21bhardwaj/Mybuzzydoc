    var templates = angular.module('templates', []);

templates.controller('TemplatesController', function($scope, $http, $window, $filter, $element){
    
    $scope.indexLoc = $window.host+"templates"
    $scope.template = new Object;
    $scope.template.id = $window.templateId;

    var config = {  
                    headers:  {
                        'accept': 'application/json',
                    }
                };

    if(!$scope.template.id){
        $scope.templateVisible = true;
        $scope.tabVisible = false;
        $scope.title = "Add Template";
        setBlank("referrals");
        setBlank("tier");

        
    }else{

        $scope.templateVisible = false;
        $scope.tabVisible = true;
        setVars();
        
    }
    
    this.createTemplate = function (){
            
        $http.post($window.host + 'api/templates/add', $scope.template, config).then(function(response){

            $scope.title = $filter('uppercase')($scope.template.name);
            $scope.templateVisible = false;
            $scope.tabVisible = true;
            $scope.template.id = response.data.response.template.id;
            

        }, function(response){
            if(response.data.code == 400)
            {

                $scope.message = response.data.message;
            }
            console.log(response);
        });

    }

    this.updateTemplate = function (col){

        $scope.template.col = col;
        if(col ==6){
            //checking if coupons are updated
            for (singletier in $scope.template.tier){
                
                for(gift_coupon in $scope.template.gift_coupon) {
                    //Check if new gift coupons which have been removed are in the tiers selected gidft _ coupon
                    if($scope.template.gift_coupon[gift_coupon] == false) {
                        if($scope.template.tier[singletier].tier_gift_coupon.gift_coupon_id == gift_coupon) {
                            //found one which is tier selected gift coupon.
                            //Throw some caution.
                            $scope.template.tier[singletier].tier_gift_coupon.gift_coupon_id = '';
                            swal("Tier Gift Coupon Deselected", "You just removed the gift coupon being used in Tiers. This can cause problems unless you update Tiers immediately. Go to tiers and hit update even if you don't want any coupon for the tier.", "error");
                        }               
                    }


 

                }                
            }
            
        }

        $http.post($window.host+'api/templates/edit/'+$scope.template.id, $scope.template, config).then(function(response){
            
            console.log(response);
            $scope.save_message= response.data.response.message;
            setVars();        
        },function(response){

            console.log('An error occured with the following response: ' + response);
        
        });

    }

    function setVars(){

        $http.get($window.host + 'api/templates/'+$scope.template.id, config).then(function(response){
            

                $scope.title = $filter('uppercase')(response.data.template.name);
                if(response.data.template.review != null){
                    $scope.template.reviews = response.data.template.review;
                }
                
                $scope.template.referrals = response.data.template.referral;
                
                console.log($scope.template.referrals);
                if($scope.template.referrals == '' || $scope.template.referrals == null){
                    setBlank("referrals");
                }

                $scope.template.tier = response.data.template.tier;
                
                if($scope.template.tier == '' || $scope.template.tier == null){
                    setBlank("tier");
                }
                else{

                    $scope.template.tier.map(function(obj){

                        obj.multiplier *= 100;


                    });
                }
                
                $scope.template.milestone = response.data.template.milestone;
                
                $scope.template.gift_coupon = response.data.template.gift_coupons;

                // if($scope.template.gift_coupon == '' || $scope.template.gift_coupon == null){
                //     setBlankGiftCoupon();
                // }

                $scope.template.promotions = response.data.template.promotion;
                
                $scope.template.survey = response.data.template.survey;

                $scope.giftCoupons = response.data.template.selected_gift_coupons;

            
        
        },function(response){

            console.log('An error occured with the following response: ' + response);
        
        });
        

    }

    function setBlank(field){
       
       $scope.template[field] = [];
       $scope.template[field][$scope.template[field].length] = {}; 
    }

    this.addNew = function(field) {

        $scope.template[field][$scope.template[field].length] = {};

    }
    
    this.tabChange = function(){

        $scope.save_message= "";
    }

    this.remove = function(field,index){

        $scope.template[field].splice(index, 1);
        console.log($scope.template);


    }

});