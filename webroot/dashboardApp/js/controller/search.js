//Injecting the Patient and Search Results factory into this controller
dashboardApp.controller('SearchController', function($location, $window,isAuthorized, $scope, $http, Patient, SearchResults, Plan, Vendor,Settings, Tour){
	var searchCtrl = this;


	//Fetch the results from cache
	SearchResults.data = [];
	$scope.results = SearchResults.data;
	$scope.topNavSearch = false;
	//Hide menu
	$scope.showMenu = false;
	//Reset the patient
	Patient.update(false);
	$scope.numRegex = "\\d+";
	$scope.vendor =  Vendor.data;
	$scope.plan = Plan.activefeatures;
	$scope.referralLevels = [];
	$scope.referralLeadId = false;
	$scope.referralLevelId = {};
	$scope.referrals = false;
	$scope.newPatientData = null;
	$scope.referral_peoplehub_identifier = false;
	$scope.referralSettingsUrl = $window.host + 'VendorReferralSettings/add';
	$scope.registerBtn = false;
	$scope.register = {};
	getSearchType();

	$scope.$watch(function(){
	
	   return $window.topNavSearch;
	
	}, function(newValue, oldValue){
		if($window.topNavSearch != ""){

			$scope.topNavSearch = $window.topNavSearch;
			
		}	
	});

	$scope.changeSearchType = function(){

		setSearchType();
	}

	function setSearchType(){
		console.log('in set');
		console.log($scope.searchtype);
		$.cookie("patient_search_type", $scope.searchtype, {expires: 365, path: '/'});
	}

	function getSearchType(){
		console.log('in get');
		$scope.searchtype = $.cookie("patient_search_type");
		console.log($scope.searchtype);
	}



	//Universal Search Function
	$scope.$watch(function(){
	
	   return $scope.topNavSearch;
	
	}, function(newValue, oldValue){
		if($scope.topNavSearch){

			$scope.query = $scope.topNavSearch;
			$scope.topNavSearch = false;
			Patient.init();
			searchCtrl.search();
			$window.topNavSearch = "";
			
		}
		
		
	});

	$scope.$watch(function(){
	
	   return SearchResults.data;
	
	}, function(newValue, oldValue){
		
		$scope.results = SearchResults.data;
	});

	$scope.$watch(function(){
	
	   return Vendor.data;
	
	}, function(newValue, oldValue){

		$scope.referrals = Vendor.data.referrals;
		$scope.referralLevels = Vendor.data.vendor_referral_settings;
		
	});

	$scope.noEmailchange = function() {
		

		if(!$scope.register.noEmail){
	
			delete $scope.register.guardian_email ;
			delete $scope.register.username;
	
		}else{

		    delete $scope.register.email;
		}
	};

	$scope.suggestUserName = function(){
		if(!$scope.register.first_name && !$scope.register.last_name){
			swal({
				title: "Please fill in your name for auto-generating a username",
				type: "warning",
			});
		}
		var data = {
		    	"first_name" : $scope.register.first_name,
		    	"last_name" : $scope.register.last_name
		    };
		    console.log(data);
		  	$http.post($window.host+'api/users/suggestUsername', data).then(function(response){

		  		console.log(response);
		  		$scope.register.username = response.data.data.username;
		  		console.log($scope.register.username);
			}, function(response){
				console.log('in error case of auto-generating username');
		  		$scope.register.username = '';
				console.log(response);
		});

	}
	//New search request
	searchCtrl.search = function(){

		// var r_s_t   = $.cookie('v_t');
		// var config = {
		// 				headers: {
		// 				            'token': 'Bearer ' + r_s_t
		// 				        	}
		//   					};

		SearchResults.update([]);
		var data={
			'search-value' : $scope.query,
			'type' : $scope.searchtype
		}
		if(typeof $scope.searchtype === 'undefined' || !$scope.searchtype){
			var url = $window.host+'api/users/searchPatient/'+$scope.query;
			// $http.get();
		}
		else{
			var url = $window.host+'api/users/searchPatient/'+$scope.query+'/'+$scope.searchtype;
		}
		$http.get(url)
		.then(function(response){
			//success
			//Store results in cache
			// console.log(response);
			SearchResults.update(response.data.data);
			$scope.results = SearchResults.data;
			$scope.resultCount = $scope.results.users.length;
			console.log($scope.results);
			// $scope.$apply();
		},function(response){
			console.log(response);
		});
	}

	//Set patient and redirect
	searchCtrl.setPatient = function(x){
		
		if(typeof x == 'undefined' || !x){
			x = null;
		}

		x = (x != null ? x : $scope.newPatientData);
		Patient.init();

		Patient.update(x);
		$scope.newPatientData = null;
		Patient.loadHistory();
		Patient.loadPatientsDetails();

		if($scope.plan.awardPoints)
		{

			$location.path('/patient');

		}else{

			if(Patient.data.id != null){
				$location.path('/redeem');
			}else{

				if(prompt("Patient is not registered on peoplehub. Click yes to register patient."))
				$location.path('/addPatient');
			}

		}

	}

	searchCtrl.cards = function(x){
		if(typeof x == 'undefined' || !x){
			return false;
		}
		return x.reduce(function(all, card){
			return all + card.card_number + 'hehe';
		})
	}

	//Register Patient and Update
	this.registerPatient = function(){


		$scope.registerBtn = true;
		console.log($scope.register);
		$scope.register.name = $scope.register.first_name+' '+$scope.register.last_name;
		var firstName = $scope.register.first_name;
		var lastName = $scope.register.last_name;
		if($scope.register.card_number){
			$scope.register.card_number = $scope.register.card_number;
		}else{
			delete $scope.register.card_number;
		}
		delete $scope.register.last_name;
		console.log($scope.register);

		$scope.register.name = toTitleCase($scope.register.name);

		$http.post($window.host+'api/users/registerPatient', $scope.register).then(function(response){

			if(response.data.status){

                $scope.referral_peoplehub_identifier = response.data.data.id;
                $scope.newPatientData = response.data.data;
				
				if($scope.auth('referrals')){


					//Prompt if Patient is a Referral
					swal({
	                title: "Is this patient a referral?",
	                text:"Click 'Yes' to select the referrer or 'No' to proceed to the new patient's account.",
	                type: "success",
	                showCancelButton: true,
	                cancelButtonText: 'No',
	                confirmButtonColor: "#DD6B55",
	                confirmButtonText: "Yes",
	                closeOnConfirm: true
	            
		            }, function (resp) {     
		                
		                if(resp){

							$('#referralModal').modal('show');
							$scope.registerBtn = false;
		                
		                }else{

		                	$scope.referral_peoplehub_identifier = false;
		                 	swalPatientAdded()
		                 	searchCtrl.setPatient();
		                }

		            });

				}else{

					swalPatientAdded();
					searchCtrl.setPatient();
				}

			}else{

				$scope.register.first_name = firstName;
				$scope.register.last_name = lastName;
				promptAddAnotherPatient(response);
			}
			$scope.registerBtn = false;

		}, function(response){
			swal({
				title: "Error",
				text: response.data.message,
				type: "error",
			});
			$scope.registerBtn = false;
			console.log(response);
		});

		$scope.register.first_name = firstName;
		$scope.register.last_name = lastName;
	}

	function promptAddAnotherPatient(response){

		swal({

	        title: response.data.title,
	        text:response.data.message,
	        type: "warning",
	        showCancelButton: true,
	        cancelButtonText: 'Ok',
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: "Add another patient",
	        closeOnConfirm: true
    
        }, function (resp) {     
            
            if(resp){

				addAnotherPatient();
            
            }

        });
	}

	function addAnotherPatient(){

		$scope.register.guardian_email = $scope.register.email;
		delete $scope.register.email;
		$scope.register.noEmail = true;
		$scope.suggestUserName();


	}

	function swalPatientAdded(){

		swal({
			title: "Patient Added Successfully",
			text: "",
			timer: 2000,
			type: "success",
			showConfirmButton: false,
		});
	}

	//Event watcher to watch when the modal closes 
	$('#referralModal').on('hidden.bs.modal', function (e) {
	
		$scope.referral_peoplehub_identifier = false;
		swalPatientAdded();
	    searchCtrl.setPatient();
	})

	//Confirm if the points should be awarded
	$scope.awardReferralPoints = function(referralLeadId){

		$scope.referralLeadId = referralLeadId;
		console.log($scope.referralLevelId[referralLeadId]);

		swal({
            title: "Are you sure?",
            text:"This cannot be undone.",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: true
        }, function (response) {     
            if(response){

            	awardRefPoints();
            
            }else{

             	$scope.referralLeadId = false;
             	delete $scope.referralLevelId[referralLeadId];            }

        });

	}

	//hit api to award points for the referral
	function awardRefPoints(){

		$scope.awardReferralButton = true; 
		referralAward = {

			referral_lead_id : $scope.referralLeadId * 1,
			vendor_referral_setting_id: $scope.referralLevelId[$scope.referralLeadId] * 1,
			referral_peoplehub_identifier: $scope.referral_peoplehub_identifier * 1
		}

		$http.post($window.host + 'api/awards/referral', referralAward).then(function(response){
           console.log(response);
           $scope.loadVendor();
           $scope.awardReferralButton = false;
           $scope.referral_peoplehub_identifier = false;
           delete $scope.referralLevelId[$scope.referralLeadId];
	       $('#referralModal').modal('hide');
	       swalPatientAdded();
	       $scope.registerBtn = false;
           

        }, function(response){

        	swal("Sorry", response.data.message, "error");
			console.log(response); 
			$('#referralModal').modal('hide');
			$scope.registerBtn = false;
			$scope.awardReferralButton = false;         
        });

		

	}

	$scope.auth = function(feature){
			return isAuthorized.check(feature);
	}


	function toTitleCase(str){

	    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}

	$scope.loadVendor = function(){

		Vendor.loadData($window.vendorId).then(function(response){
			//success
			//adding another option in vendor referral settings 
			Vendor.update(response.data.vendor);
			console.log(response.data.vendor);
			$scope.vendor = Vendor.data;
			Plan.update($scope.vendor.vendor_plans[0].plan.plan_features);
			$scope.plan = Plan.activefeatures;
			Settings.update($scope.vendor.vendor_settings);
			$scope.topNavSearch = $window.topNavSearch;
			Tour.init();
		},

		function(response){
			//error
		})

	}

	if(!$scope.vendor) {

		$scope.loadVendor();
		
	}


});
