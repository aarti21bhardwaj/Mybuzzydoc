var requeststatus = angular.module('requeststatus', []);

requeststatus.controller('RequeststatusController', function($scope, $http, $window, $filter, $element, $parse){

    var the_string = '';
    var model = '';
    $scope.awrdPts= false;
    getAwards();
    
    this.points = function(requestId, reviewType){ 
        
        

        $scope.request = {

            review_request_status_id : requestId,
            review_type_name : reviewType 

        };



        swal({
                title: "Are you sure you want to award the points?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function () {
                
                awardPoints(requestId, reviewType);

            });


    };

    function awardPoints(requestId, reviewType){
        
        $scope.awrdPts= true;
        
        $http.post($window.host + 'api/awards/review', $scope.request).then(function(response){
          
           swal("Points Awarded Successfully" , " ","success");
           the_string = reviewType+requestId;
           model = $parse(the_string);
           model.assign($scope, true);
           $scope.awrdPts= false;
           

        }, function(response){

            swal("Awarding of points Failed" ,"" ,"error");
            console.log(response);  
            $scope.awrdPts= false;        
        });

    }

    function getAwards(){
        
        
        $http.get($window.host + 'api/awards/reviewAwardsIndex').then(function(response){
            
           $scope.awards = response.data.reviewAwards;
           $scope.reviewTypes = response.data.reviewTypes;
           $scope.reviewSettings = response.data.reviewSettings;
           console.log(response);
           setAwards();

        }, function(response){

            console.log(response);          
        });

    }

    function setAwards(){
        
        var rTypes = {};
        
        for(type in $scope.reviewTypes){

            rTypes[$scope.reviewTypes[type].id] = $scope.reviewTypes[type].name;
        }

        for(award in $scope.awards){

           the_string = rTypes[$scope.awards[award].review_type_id] + $scope.awards[award].review_request_status_id;
           model = $parse(the_string);
           model.assign($scope, true);

        }
        

    }
    

   

});