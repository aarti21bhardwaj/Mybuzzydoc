dashboardApp.controller('ReviewController', function($window, $scope, $http, Patient, Vendor){

	$scope.hostUrl = $window.host;
	
	init();

	function init(){

		$scope.pdata = Patient.data;
		$scope.addLocation = $window.host+"vendor-locations/add";
		$scope.editLocation = $window.host+"vendor-locations/edit/";
		$scope.addReviewSettings = $window.host+"vendor-locations/index#setPointsModal";
		$scope.vendor = Vendor.data;
		$scope.request = new Object;
		$scope.reqBtn = false;
		$scope.awrdPts= false;
		$scope.currentLocationId = "";
		// $scope.request.email = "james.kukreja@gmail.com"
		if($scope.vendor.vendor_locations.length == 0){
			$scope.message = "No Vendor Locations Exists.";
		}
		else{

			checkUrls();
			getPatientReviewInfo();
		}
		//$scope.pdata.email;
		checkEmail();


	}
	

	this.requestReview = function(id){

		$scope.reqBtn = true;
		$scope.request.vendor_location_id = id;
		if($scope.pdata.id != null){
			$scope.request.people_hub_identifier = $scope.pdata.id;
		}
		if(typeof $scope.pdata.phone != "undefined" 
			   && $scope.pdata.phone != null 
			   && $scope.pdata.phone.length > 0){
			
			$scope.request.phone = $scope.pdata.phone;
		}
		$scope.request.patient_name = $scope.pdata.name;
		$scope.request.peoplehub_user_id = $scope.pdata.id;

		
		$http.post($window.host + 'api/review-request-statuses/request-review', $scope.request).then(function(response){
          	
           swal("Request for Review has been sent" , " ","success");
           console.log(response);
           $scope.reqBtn = false;
           getPatientReviewInfo();

        }, function(response){

        	swal("Request for Review could not be sent" , " ","error");
			console.log(response);
			$scope.reqBtn = false;          
        });



	}

	this.refreshVendor = function(){

		Vendor.loadData($window.vendorId).then(

			function(response)
				{
				//success
				 console.log(response.data.vendor);

				Vendor.update(response.data.vendor);
				$scope.vendor = Vendor.data;
				init();
				},
			function(response){
				//error
				})

	}

	function getPatientReviewInfo(){

		$http.get($window.host + 'api/ReviewRequestStatuses/getPatientReviewInfo/'+$scope.pdata.id).then(function(response){
          
           $scope.patientReviewInfo = response.data.patientReviewInfo;

        }, function(response){

        	console.log('Error in response in getPatientReviewInfo');
			console.log(response);
        });

	}

	this.awardPoints =function(requestId, reviewType){
        
        $scope.awrdPts= true;
        
        awardPointsRequest = {

        	review_request_status_id: requestId,
        	review_type_name:reviewType

        };

        $http.post($window.host + 'api/awards/review', awardPointsRequest).then(function(response){
          
           swal("Points Awarded Successfully" , " ","success");
           $scope.awrdPts= false;
           getPatientReviewInfo();
           Patient.loadEverything();
           

        }, function(response){

            swal("Awarding of points Failed" , response.data.message ,"error");
            console.log(response);  
            $scope.awrdPts= false;        
        });

    }

	function checkUrls(){

		$scope.emptySettings = {};
		settings = {
			fb_url : "Facebook", 
			google_url: "Google Plus", 
			yelp_url: "Yelp",
			ratemd_url: "RateMd",
			yahoo_url: "Yahoo",
			healthgrades_url: "Healthgrades"
		}

		for(var vl in $scope.vendor.vendor_locations)
		{
			$scope.emptySettings[vl] = []; 
			for(var setting in settings)
			{

				if(typeof $scope.vendor.vendor_locations[vl][setting] == "undefined" 
					   || $scope.vendor.vendor_locations[vl][setting] == null 
					   ||$scope.vendor.vendor_locations[vl][setting].length == 0){

					$scope.emptySettings[vl].push(settings[setting]);
				} 
				   
			}
		}


	}

	function checkEmail(){

		if(typeof $scope.pdata.email != "undefined" 
			   && $scope.pdata.email != null 
			   && $scope.pdata.email.length > 0){

			$scope.request.email_address = $scope.pdata.email;


		}else if(typeof $scope.pdata.guardian_email != "undefined" 
			   && $scope.pdata.guardian_email != null 
			   && $scope.pdata.guardian_email.length > 0){

			$scope.request.email_address = $scope.pdata.guardian_email;

		}else{

			$scope.emailMessage = "No Email or Guardian's Email is set for the Patient."

		}

	}

	this.toggleModal = function(locationId){
		console.log(locationId);
		$scope.currentLocationId = locationId;
		$('#reviewModal').modal('show');

	}


});

// $(document).on('ready', function(){
 
//     $('.strRate').rating({displayOnly: true, step: 0.5});
// });