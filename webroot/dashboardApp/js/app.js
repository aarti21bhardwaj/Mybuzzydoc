var env = {};

// Import variables if present (from env.js)
if(window){  
  env = window.host;
}

if(window){  
  vendor_id = window.vendor_id;
}


var dashboardApp = angular.module("dashboardApp",["ngRoute", "ngCookies", "datatables", "NgSwitchery", "dyFlipClock"]);

dashboardApp.constant('apiHost', env);
dashboardApp.constant('vendor_id', vendor_id);

dashboardApp.config(['$locationProvider', '$routeProvider','apiHost', function($locationProvider, $routeProvider, apiHost){
	  			$locationProvider.hashPrefix('!');

                $routeProvider
                .when('/',{templateUrl:apiHost+'dashboardApp/views/index.html'})
                .when('/patient',{templateUrl:apiHost+'dashboardApp/views/patient.html'})
                .when('/redeem',{templateUrl:apiHost+'dashboardApp/views/redeem.html'})
                .when('/requestReview',{templateUrl:apiHost+'dashboardApp/views/request_review.html'})
                .when('/editProfile',{templateUrl:apiHost+'dashboardApp/views/edit_profile.html'})
                .when('/addPatient',{templateUrl:apiHost+'dashboardApp/views/add_patient.html'})
                .when('/survey', {templateUrl: apiHost+'dashboardApp/views/survey.html'})
                .when('/activityHistory', {templateUrl: apiHost+'dashboardApp/views/activity_history.html'})
                .when('/addLead', {templateUrl: apiHost+'dashboardApp/views/referral.html'})
                .when('/oldBuzzyDocHistory', {templateUrl: apiHost+'dashboardApp/views/old_buzzy_history.html'})
                .when('/sendFlower', {templateUrl: apiHost+'dashboardApp/views/send_flowers.html'})
                .when('/patientAssessment', {templateUrl: apiHost+'dashboardApp/views/assessment_survey.html'})
                .when('/patientAddress', {templateUrl: apiHost+'dashboardApp/views/patient_address.html'})
                .otherwise({redirectTo:'/'});
            }]);
dashboardApp.config(function($httpProvider){
	$httpProvider.interceptors.push('apiInterceptor');
});

//Dashboard App Run method

dashboardApp.run(['Patient', '$location', function(Patient, $location){
	if(Patient.data == false){
		$location.path('/');
	}
}]);
