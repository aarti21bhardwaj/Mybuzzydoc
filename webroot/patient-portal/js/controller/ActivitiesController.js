Bountee.controller('ActivitiesController', function($location, $state, $window, $scope, $http, UsersFactory,VendorsFactory, $sessionStorage, SweetAlert){
  var vm = this;
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


});
