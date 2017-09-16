dashboardApp.controller('SurveyController', function($window, $scope, $http, Vendor, Patient, isAuthorized){
	var config = {
 		headers:{"accept":"application/json"}
	};

	$scope.request = {};
	$scope.pdata = Patient.data;
	$scope.surveyQuestions = Vendor.data.vendor_surveys[0];
	$scope.submitButton = false;
	$scope.submitButtonText = 'Submit';
	$scope.request.survey_instance_responses = {};
	$scope.frequency = {};
	$scope.surveyMessage= "";
	$scope.surveyCheck = false;
	$scope.showForPerfect = isAuthorized.check('forperfectsurvey');
	$scope.latestSurveyDate = '';

	if(typeof $scope.surveyQuestions != "undefined"){
	
		getPreviousResponses();
		
	}else{

		$scope.surveyMessage= "No Survey is available";
	}

	this.submitSurvey = function(){

		
		$scope.submitButton = true;

		if($scope.pdata.id){

					$scope.request.patient_peoplehub_id = $scope.pdata.id;
					$scope.request.patient_name = $scope.pdata.name;
					$http.post($window.host + '/api/ComplianceSurvey/saveResponses', $scope.request).then(function(response){
			          	
			           swal("Congrats!", "The survey responses have been saved.", "success");
			           console.log(response);
			     	   $scope.submitButtonText = 'Submited';
			     	   $scope.surveyCheck = false;
			     	   $scope.surveyMessage= "Survey has been submitted.";
			           Patient.loadEverything();
			           

			        }, function(response){

			        	swal("Sorry", "There was some error in awarding points", "error");
						console.log(response); 
						$scope.submitButton = true;         
					});
		}else{

			swal("Not Registered!","Patient Not Registered, Kindly Register Patient", "error");
		}

	}

	this.nextSlide = function(){

		Vendor.surveySwiper.slideNext();
	}

	this.checkQuestions = function(){

		for(key in $scope.request.survey_instance_responses){

			if(typeof $scope.request.survey_instance_responses[key].response == "undefined"){

		 		return true;

		 	}

		}
		
		return false;

	}

	function setRequest(){
		
		date = Date.parse(Date());
		$scope.request.patient_peoplehub_id = $scope.pdata.id;
		for(key in $scope.surveyQuestions.vendor_survey_questions){
			
			vSurveyId = $scope.surveyQuestions.vendor_survey_questions[key].id;
			frequency = $scope.surveyQuestions.vendor_survey_questions[key].survey_question.question.frequency;
			if(frequency == 1 || frequency == 0){
				
				forPerfect = true;
			
			}else{

				forPerfect = false;
			}

			if(typeof $scope.previousResponse[vSurveyId] == "undefined"){

				initQuestions(key, vSurveyId, forPerfect);

			}else if(typeof $scope.previousResponse[vSurveyId] != "undefined" && $scope.previousResponse[vSurveyId].response != "1"){

				initQuestions(key, vSurveyId, forPerfect);

			}else{


				created = Date.parse($scope.previousResponse[vSurveyId].created);
				
				if((date-created)/(1000*3600*24) >= frequency || frequency == 0){

					initQuestions(key, vSurveyId, forPerfect);
				}	
			}
			if(!$scope.surveyCheck){

				$scope.surveyMessage= "Survey Completed. Last survey was taken on "+$scope.latestSurveyDate;

			} 	
		}

		console.log($scope.request);

	}

	function setFreshRequest(){

		for(key in $scope.surveyQuestions.vendor_survey_questions){
			
			vSurveyId = $scope.surveyQuestions.vendor_survey_questions[key].id;
			frequency = $scope.surveyQuestions.vendor_survey_questions[key].survey_question.question.frequency;
			if(frequency == 1 || frequency == 0){
				
				forPerfect = true;
			
			}else{

				forPerfect = false;
			}

			initQuestions(key, vSurveyId, forPerfect);
				
		}
		
	}

	function initQuestions(key, vSurveyId, forPerfectSurvey){


		$scope.request.survey_instance_responses[vSurveyId] = {};
		$scope.request.survey_instance_responses[vSurveyId].vendor_survey_question_id = $scope.surveyQuestions.vendor_survey_questions[key].id;
		$scope.request.survey_instance_responses[vSurveyId].forPerfectSurvey = forPerfectSurvey;
		$scope.frequency[vSurveyId] = true;
		$scope.surveyCheck = true;

	}

	function getPreviousResponses(){


		$http.get($window.host + '/api/ComplianceSurvey/getResponses/'+$scope.surveyQuestions.id+'/'+$scope.pdata.id, $scope.request).then(function(response){
			          	
			           
			           console.log(response);
			           $scope.previousResponse = response.data.data;
			           if(typeof $scope.previousResponse == 'undefined' || $scope.previousResponse == null){

							setFreshRequest();
						}else{
							$scope.latestSurveyDate = response.data.latestSurveyDate;
							console.log($scope.latestSurveyDate);
							setRequest();
						}

			        }, function(response){

						console.log("An error occured in getting previous responses");
						console.log(response); 
					});   
		

	}

	// function checkemail(){

	// 	if(typeof $scope.pdata.email !== "undefined" && $scope.pdata.email != null){

	// 		$scope.request.attribute_type =  "email";
	// 		$scope.request.attribute 		= $scope.pdata.email;
	// 		return true;

	// 	}else if(typeof $scope.pdata.phone != "undefined" && $scope.pdata.phone != null){
			
	// 		$scope.request.attribute_type =  "phone";
	// 		$scope.request.attribute 		= $scope.pdata.phone;
	// 		return true;

	// 	}else if(typeof $scope.pdata.email == "undefined" && typeof $scope.pdata.phone == "undefined"){
			
	// 		return false;
			
	// 	}else if($scope.pdata.email == 'null' && $scope.pdata.phone == 'null'){

	// 		return false;
	// 	}
	// }
});

function swiperInit() {
     var mySwiper = new Swiper ('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: false,
            effect:'flip',
            mousewheelControl: true,
            centeredSlides: true,
            autoHeight : false,
            // calculateHeight:true,
            setWrapperSize: false,
            // If we need pagination
            pagination: '.swiper-pagination',
            
            // Navigation arrows
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
        });
     return mySwiper;
};