dashboardApp.controller('EditProfileController', function($window, $scope, $http, Patient, SearchResults){
	var config = {
 		headers:{"accept":"application/json"}
	};
 	
 	init();
 	function init(){
 		// console.log(patient);
		$scope.pdata = Patient.data;
		$scope.numRegex = "\\d+";
		$scope.editRequest = {};
		$scope.saveBtn = true;
 		$scope.relationships = [

			{id:1, name:"Mother"},
			{id:2, name:"Father"}

		];
		if(typeof $scope.pdata.email != "undefined" && $scope.pdata.email != "" && $scope.pdata.email != null){

			$scope.editRequest.noEmail = false;

		}else{

			$scope.editRequest.noEmail = true;
		}	
 		extract();	
 	}

 	this.changeNoEmail = function(){

 		noEmail();

 	}

 	function extract(){
 		console.log('here');
 		console.log($scope.pdata.name);
 		var str1 = myTrim($scope.pdata.name);
 		console.log(str1);

 		var str2 = str1.match(/^(\S+)\s(.*)/);
 		console.log(str2);
 		if(str2 != null && str2.length > 0){
	 		var str3 = str2.slice(1);
	 		console.log(str3);
	 		var str4 = [];
	 		for(index in str3){
	 			str4.push(myTrim(str3[index]));
	 		}
	 		console.log(str4);
	 		$scope.pdata.first_name = str4[0];
	 		if(str4[1]!= null){
		 		$scope.pdata.last_name = str4[1];
		 	}
	 	}else{
	 		$scope.pdata.first_name = str1;
	 		$scope.pdata.last_name = '';
	 	}
 		if(!$scope.editRequest.noEmail){
 			data = ['first_name', 'last_name', 'email', 'phone'];
 		}else{

 			data = ['first_name', 'last_name', 'phone', 'guardian_email', 'username'];
 		}

 		for(key in data){
 			if(typeof $scope.pdata[data[key]] != "undefined" && $scope.pdata[data[key]] != "")

 				$scope.editRequest[data[key]] = angular.copy($scope.pdata[data[key]]);
 		}

 		if(typeof $scope.editRequest['phone'] != "undefined" && $scope.pdata['phone'] != "" && $scope.pdata['phone'] != null){

 			$scope.editRequest['phone'] = $scope.editRequest['phone'].split('-').join('');
 		}
 	}
 	

 	function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}

	function noEmail() {
		

		if(!$scope.editRequest.noEmail){
	
			delete $scope.editRequest.guardian_email ;
			delete $scope.editRequest.username;
	
		}else{

		    delete $scope.editRequest.email;

		}
	}
 
 this.editPatient = function(){

 		$scope.saveBtn = false;
 		$scope.editRequest.name = $scope.editRequest.first_name+' '+$scope.editRequest.last_name;
 		var firstName = $scope.editRequest.first_name;
		delete $scope.editRequest.first_name;
		var lastName = $scope.editRequest.last_name;
		delete $scope.editRequest.last_name;
		$scope.editRequest.name = toTitleCase($scope.editRequest.name);

		$http.post($window.host+'api/users/editPatientProfile/'+$scope.pdata.id, $scope.editRequest).then(function(response){

			if(response.data.status){

				swal("Saved", "Patient Profile Saved", "success");
				SearchResults.data = {};
				Patient.update(response.data.data);
				Patient.loadHistory();
				Patient.loadPatientsDetails();
					
			}else{
				swal({
					title: "Oops! Something didn't go well!",
					text: response.data.message,
					type: "error",
				});
			}
			$scope.saveBtn = true;

		}, function(response){
			swal({
				title: "Oops! Something didn't go well!",
				text: response.data.message,
				type: "warning",
			});
			$scope.saveBtn = true;
			
		});
		$scope.editRequest.first_name = firstName;
		$scope.editRequest.last_name = lastName;
	}

	function toTitleCase(str){

	    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}


});
