var setupwizard = angular.module('setupwizard', []);

setupwizard.controller('SetupwizardController', function($scope, $http, $window, $timeout) {

	init();

	function init(){
		
		$scope.radio = {};
		$scope.checkbox = {};
		$scope.templateRadio = {};

		$http.get($window.host + 'api/setUpWizard/index')	 //concatinated mmonitoring id here as Url builder was interfering with '&'
			 	.then(function(response){
			 		$scope.questionnaire = response.data.questionnaire;
			 		$scope.planName = response.data.plan;
			 	});
 	}

 	$scope.set = 1;		//set of questions.

 	$scope.view = 0;	//view on the page. zero means survey view.

 	this.questionNavigation = function(val){

 		$scope.firstResponse = $scope.radio[1];
 		$scope.set = $scope.set + val;

 		// if first question not answered don't let the slide to navigate to last set of questions instead go back to 1st slide with an alert.
 		if(!$scope.firstResponse && $scope.set == 3){
 			$scope.set = 1;
 			alert('Answer this question first.');
 		}
 	}

 	this.responseSubmission = function(){

 		vendorSettingsAdd();
 		templateFinalization();
 		getAllTemplates();

 		//changing the view here.
		$scope.view = 1; 		
 	}

 	function getAllTemplates(){

 		$http.get($window.host + 'api/templates/index/').then(function(response){
            $scope.allTemplates = response.data.templates;
              	console.log(response);
       }, function(response){
           console.log('Error');
           console.log(response);
       });
 	}

	$scope.settingKeys = [];
 	function vendorSettingsAdd(){

 		// putting all the setting key ids in an array to be saved.
 		
 		for(x in $scope.checkbox){
 			if($scope.checkbox[x]){
 				$scope.settingKeys = $scope.settingKeys.concat($scope.checkbox[x]);
 			}
 		}
 		console.log($scope.settingKeys);
 	}

 	$scope.sortedTemplates = [];
 	function templateFinalization(){

 		var templatesInitial = [];
 		for (x in $scope.radio){
 			
 			if($scope.radio[x][$scope.firstResponse]){
	 			templatesInitial = templatesInitial.concat($scope.radio[x][$scope.firstResponse]);
 			}
 		}

 		//counting every template's occurrence
		var templateCount = { };
		for (var i = 0, j = templatesInitial.length; i < j; i++) {
		   templateCount[templatesInitial[i]] = (templateCount[templatesInitial[i]] || 0) + 1;
		}

		//making the object an array so that it could be sorted.
		var sortable = [];
		for (var temps in templateCount){
    		sortable.push([temps, templateCount[temps]])
		}

		//sorting them on the basis of their occurrence
		sortable.sort(function(a, b) { 
		    return b[1] - a[1];
		})

		$scope.sortedTemplates = sortable;
		console.log($scope.sortedTemplates);
 	}

	$scope.templateId = '';
	$scope.viewTemplate = '';
	this.getTemplate = function(id)
    {
        $scope.templateId = id;
        $http.get($window.host + 'api/templates/templateDetails/'+id).then(function(response){
            $scope.viewTemplate = response.data.template;
              	console.log(response);
    			console.log($scope.viewTemplate);
       }, function(response){
           console.log('Error');
           console.log(response);
       });
    }

	this.templateSubmission = function(fullStar){

		$scope.selectedTemplate = '';
		//if fullStar is true meaning if recommended template is selected 
		// then it has to be the first element of the first array in $scope.sortedTemplates.
		if(fullStar){
			$scope.selectedTemplate = $scope.sortedTemplates[0][0];
		}else{
			$scope.selectedTemplate = $scope.templateRadio.id;
		}

		var data = {

			'template_id': $scope.selectedTemplate,
			'vendor_settings': $scope.settingKeys,
		}

		$http.post($window.host + 'api/setUpWizard/saveSetupWizard', data)
            .then(function(response){
            	console.log(response);
            	$window.location.href = $window.host + 'users';
            }, function(response){
            	//failure callback
            	alert('Template could not be applied at the moment.')
            });
	}

});
