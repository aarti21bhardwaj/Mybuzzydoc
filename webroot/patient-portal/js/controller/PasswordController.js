Bountee.controller('PasswordController', function($uibModal, $uibModalInstance,$rootScope,$location, $state, $window,notify,$compile, $scope, $http, UsersFactory,VendorsFactory,FacebookFactory, $sessionStorage, SweetAlert){
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

  $scope.updatePassword = function () {
    var updatePwd = true;
    if(vm.new_password == vm.old_password){
      updatePwd = false;
      notify({
        message: 'You are not allowed to use new password same as old password',
        classes: 'alert-danger',
        templateUrl: 'views/common/custom-notify-danger.html'
      });
      $scope.customAlert = true;
    }
    if(vm.new_password != vm.cnf_password){
      updatePwd = false;
      notify({
        message: 'Password does not matched with Confirm Password. Kindly enter correct password.',
        classes: 'alert-danger',
        templateUrl: 'views/common/custom-notify-danger.html'
      });
      $scope.customAlert = true;
    }
    request = {
      'password':vm.new_password,
      'old_password':vm.old_password
    };
    if(updatePwd){
      UsersFactory.updateMe(request,function(err,data){
        if (err) {
          notify({
            message: err.message,
            classes: 'alert-danger',
            templateUrl: 'views/common/custom-notify-danger.html'
          });
          $scope.customAlert = true;
        } else {
          if(data.status){
            notify({
              message: 'Password updated successfully.',
              classes: 'alert-success',
              templateUrl: 'views/common/custom-notify-success.html'
            });
            $scope.customAlert = true;
          }else{
            notify({
              message: 'Something went wrong, Kindly Try again later.',
              classes: 'alert-danger',
              templateUrl: 'views/common/custom-notify-danger.html'
            });
            $scope.customAlert = true;
          }
        }
      });
  }
}

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };

});
