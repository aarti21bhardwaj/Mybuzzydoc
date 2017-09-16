var app = angular.module('PatientReportedOutcomes', ['ui.swiper'] );

//To use spinner;
//At the start of the api add
	/*
		var loading = document.getElementById('loading-bar');
		loading.style.display = 'block';
	*/
//At the end of the api add
	/*
		var loading = document.getElementById('loading-bar');
		loading.style.display = 'none';
	*/

app.controller('MainController', function($scope, $window, $http){
	
	var config = {  
                    headers:  {
                        'accept': 'application/json',
                  }
    };

	function init(){
		$scope.showContent = true;
		$scope.swiper = false;
		$scope.surveyInstanceId = $window.surveyId;
		$scope.request = {assessment_survey_instance_responses:[]};
		$scope.assessmentSurvey = false;
		$scope.smileyClass = {0 : 'fa-smile-o', 1 : 'fa-meh-o', 2 : 'fa-frown-o'};
		getResponseOptions();
		
		if(!angular.isUndefined($scope.surveyInstanceId)){
			getSurveyInstance();
		}

	}

	function getSurveyInstance(){

		var loading = document.getElementById('loading-bar');
		loading.style.display = 'block';
        
        $http.get($window.host + 'api/assessmentSurveyInstances/getVendorAssessmentSurvey/'+$scope.surveyInstanceId, config).then(function(response){
                
            console.log(response.data);
            $scope.assessmentSurvey = response.data.vendorAssessmentSurvey;
            loading.style.display = 'none';

        },function(response){

        	swal('Error!', response.data.message, "error");
            console.log('error case of get survey instance');
            console.log(response);
			loading.style.display = 'none';        
        });
    }

	function getResponseOptions(){

		var loading = document.getElementById('loading-bar');
		loading.style.display = 'block';

		$http.get($window.host + 'api/responseOptions/').then(function(response){
			       
           console.log(response);
           $scope.responseOptions = response.data.responseOptions;
			loading.style.display = 'none'; 	

        }, function(response){

			console.log("An error occured in getting response options");
			console.log(response); 
			loading.style.display = 'none'; 				
		});   
	}

	function validateResponse(){

		var valid = true;
			$scope.assessmentSurvey.vendor_assessment_survey_questions.map(function(value, key){
				if(!(!angular.isUndefined($scope.request.assessment_survey_instance_responses[key].response_option_id) 
					&& $scope.request.assessment_survey_instance_responses[key].response_option_id 
					&& $scope.request.assessment_survey_instance_responses[key].vendor_assessment_survey_question_id == value.id)){
					valid = false;
			}
		});

		return valid;
	}

	$scope.saveResponse = function(){


		if(!validateResponse()){

			swal("Error", "Kindly complete the survey.", "error");
			return false;

		}else{

			var loading = document.getElementById('loading-bar');
			loading.style.display = 'block';
			$scope.showContent = false;

			$http.post($window.host + 'api/assessmentSurveyInstances/submitPatientReportedOutcome/'+$scope.surveyInstanceId, $scope.request, config).then(function(response){

				// swal('Success!', "The response has been submitted", 'success');
				$window.location = $window.host +'assessmentSurveyInstances/patientReportedOutcomes' + '?key=' + $scope.surveyInstanceId;
				$scope.showContent = true;
				


			}, function(response){

				console.log("An error occured in getting response options");
				console.log(response); 
				swal('Error!', response.data.message, 'error');
				loading.style.display = 'none'; 
				$scope.showContent = true;
			}); 
		}
	}
	$scope.initResponse = function(index, vendorAssessmentSurveyQuestionId){
		$scope.request.assessment_survey_instance_responses[index] = {vendor_assessment_survey_question_id: vendorAssessmentSurveyQuestionId};
	}

	$scope.nextSlide = function(){
		$scope.swiper.slideNext();
	}

	$scope.getRadioStyle = function(responseGroupId){

		if(responseGroupId == 2){
			return {visibility: 'hidden', position: 'absolute'};
		}

	}

	$scope.getSmileyStyle = function(id, index){

		if(id == $scope.request.assessment_survey_instance_responses[index].response_option_id){
			return {cursor:'pointer', color:'black'};
		}else{
			return {cursor:'pointer', color:'grey'};
		}
	}


	$scope.onInit = function(swiper){

    	$scope.swiper = swiper;
    	// $scope.swiper.params.slidesOffsetAfter = 20;
    	// console.log('here');
  	};
	init();
});