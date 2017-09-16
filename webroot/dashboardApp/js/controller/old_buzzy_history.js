dashboardApp.controller('OldBuzzyHistoryController', function($window, $location, $scope, $http, Vendor, Patient, isAuthorized){

	$scope.oldHistory = [];
	Patient.getOldBuzzyHistory().then(function(response){      	
		       
	   Patient.oldBuzzyHistory = $scope.oldHistory = response.data;
       console.log(response);
       

    }, function(response){

    	
    	swal({

	        title: "Sorry!",
	        text:response.data.message,
	        type: "warning",
	        showCancelButton: false,
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: "OK",
	        closeOnConfirm: true
    
        });

        $location.path('/patient');
		console.log(response); 
		
    });
	
});