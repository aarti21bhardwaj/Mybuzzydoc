dashboardApp.controller('AssessmentSurveyController', function($timeout,$window, $route,$scope,$http,Plan, Settings, isAuthorized,Patient, Vendor){

	init();

	function init(){
		$scope.tab1 = true;
		$scope.surveySwiper = false;
		$scope.assessmentSurvey = false;
		$scope.history = false;
		$scope.modalHeading = false;
		$scope.request = {

			assessment_survey_instance_responses: [],
			email: Patient.getEmail(),
			phone: Patient.getPhone(),
			patient_peoplehub_id: Patient.data.id,
			patient_name: Patient.data.name,

		};
		$scope.disableSubmit = false;
		$scope.smileyClass = {0 : 'fa-smile-o', 1 : 'fa-meh-o', 2 : 'fa-frown-o'};
		$scope.responseOptions = {};
		$scope.users = Vendor.data.users;
		$scope.showSurvey = false;
		$scope.hideSurveyMessage = "";
		$scope.lastSurveyToday = false;
		getResponseOptions();
		getHistory();
		getSurvey();


		
	}

	function getSurvey(){
		if(Vendor.data.vendor_assessment_surveys.length > 0){
			$scope.assessmentSurvey = Vendor.data.vendor_assessment_surveys[0];
		}
	}

	function getResponseOptions(){
		$http.get($window.host + 'api/responseOptions/').then(function(response){
			       
           console.log(response);
           $scope.responseOptions = response.data.responseOptions;

        }, function(response){

			console.log("An error occured in getting response options");
			console.log(response); 
		});   
	}

	function checkShowSurvey(){

		$scope.showSurvey = true;
		
		if(angular.isUndefined($scope.assessmentSurvey) || !$scope.assessmentSurvey){
			$scope.showSurvey = false;
			$scope.hideSurveyMessage = "No Survey is Available";
		}

		if($scope.assessmentSurvey.vendor_assessment_survey_questions.length == 0){
			$scope.showSurvey = false;
			$scope.hideSurveyMessage = "No questions are associated with the survey.";	
		}

		if($scope.lastSurveyToday){
			$scope.showSurvey = false;
			$scope.hideSurveyMessage = "Survey has been submitted for today.";
		}
		
	}

	function getHistory(){

		$http.get($window.host + 'api/assessmentSurveyInstances/getHistory/'+Patient.data.id).then(function(response){
			       
           console.log(response);
           $scope.history = response.data.history;
           $scope.lastSurveyToday = response.data.wasLastSurveyTakenToday;
           checkShowSurvey();

        }, function(response){

			console.log("An error occured in getting response options");
			console.log(response); 
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

		if(angular.isUndefined($scope.request.user_id) || $scope.request.user_id== "" || !$scope.request.user_id){
			valid = false;
		}

		return valid;
	}

	$scope.tabSwitch = function(x){
	
		if(typeof x == "undefined"  || !x || x == null || x == ""){
			return;
		}

		var tabs = ['tab1', 'tab2', 'tab3', 'tab4', 'tab5']
		for(key in tabs)
		$scope[tabs[key]] = false;
		$scope['tab'+x] = true;	
	}

	$scope.surveyInit = function(){

		$scope.surveySwiper = $window.swiperInit();
	}

	$scope.saveResponse = function(){

		if(!validateResponse()){
			
			swal("Error", "Kindly complete the survey.", "error");
			return false;
			
		}else{
			$scope.disableSubmit = true;
			$scope.request.vendor_assessment_survey_id = $scope.assessmentSurvey.id;
			$http.post($window.host + 'api/assessmentSurveyInstances/', $scope.request).then(function(response){
				       
	           swal('Success!', "The response has been submitted", 'success');
	           getHistory();

	        }, function(response){

				console.log("An error occured in getting response options");
				console.log(response); 
	           	$scope.disableSubmit = false;
				swal('Error!', response.data.message, 'error');
			}); 
		}
	}

	$scope.initResponse = function(index, vendorAssessmentSurveyQuestionId){
		$scope.request.assessment_survey_instance_responses[index] = {vendor_assessment_survey_question_id: vendorAssessmentSurveyQuestionId};
	}

	$scope.nextSlide = function(){

		$scope.surveySwiper.slideNext();
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

	$scope.initGraph = function(surveyTypeId){
		
		if(angular.isUndefined($scope.history[surveyTypeId]) || $scope.history[surveyTypeId].length <= 0){
			swal("Error!", "No Survey results found.", "error");
			return;
		}

		$('#graphModal').modal('show');
		if(surveyTypeId == 1){
			$scope.modalHeading = "Staff Assessment Survey Graph";
		}else{
			$scope.modalHeading = "Patient Reported Outcomes Graph";
		}

		var xAxis = ['x'];
		var data = ['Score'];
		$scope.history[surveyTypeId].map(function(value, key){
			xAxis.push(value.date);
			data.push(value.score);
		});
		console.log(xAxis);
		console.log(data);
		var chart = $window.c3.generate({
			 size: {

		        width: 800
		    },
		    bindto: '#lineChart',
		    data: {
				      x: 'x',
				      columns: [xAxis ,data],
				      // xFormat:'%Y-%m-%d'
		    },
		    axis: {
   					x: {
   						type: 'timeseries',
   						tick: {
			                format: '%Y-%m-%d',
			            	rotate: 90
			            },
   					}	
       		}
		});
	}
});