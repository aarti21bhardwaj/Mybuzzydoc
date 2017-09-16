Bountee.controller('EventController', function($location, $state, $window, $scope, $http, UsersFactory,VendorsFactory, $sessionStorage, SweetAlert, buzzyEnv,mode,bounteeSandbox,bounteeLive){
  var vm = this;
  
  var host = buzzyEnv;
  var buzzy_host = buzzyEnv;
  if(mode != 0){
    host = bounteeLive;
  }

  $scope.emailRequest = {};
  $scope.emailRequest.events = {};
  
  $scope.$watch(function(){
    return UsersFactory.getUsersData();
  }, function(newValue, oldValue) {
    $scope.userData = UsersFactory.getUsersData();
  });
  $scope.$watch(function(){
    return VendorsFactory.getVendorData();
  }, function(newValue, oldValue) {
    $scope.vendorDetails = VendorsFactory.getVendorData();
  });

  getunsubscribeEvent();


  // $scope.changeEvent = function(){
  // 	console.log($scope.emailRequest);
  // }

  this.unsubscribeEvent = function(){
  	var req = {
  		'vendor_id': $scope.vendorDetails.id,
  		'patient_id': $scope.userData.id,
  		'event':  $scope.emailRequest.events
  	}
    // console.log(req);
  	var req = {
      method: 'POST',
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/unsubscribeEvent",
      headers: {
        'mode': mode
      },
      data: req
    };
    $http(req).then(function(successCallback) {
        console.log(successCallback.data);
        swal("Done!", "Email Settings applied successfully", "success");
        console.log($scope.emailRequest.events);
    });

  };

  function getunsubscribeEvent(){
  	console.log('in get unsubscribe');
  	var req = {
  		'vendor_id': $scope.vendorDetails.id,
  		'patient_id': $scope.userData.id,
  	}
  	var req = {
      method: 'POST',
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/unsubscribeEvent",
      headers: {
        'mode': mode
      },
      data: req
    };
    $http(req).then(function(successCallback) {
  	  
       for(x in successCallback.data){
        $scope.emailRequest.events[x] = false; 
       }
     
    }, function(res){
      console.log('in Error');
    });

  };

});