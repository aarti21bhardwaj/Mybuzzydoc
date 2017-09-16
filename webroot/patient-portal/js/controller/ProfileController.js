Bountee.controller('ProfileController', function($uibModal, $rootScope,$location, $state, $window,notify,$compile, $scope, $http, UsersFactory,VendorsFactory,FacebookFactory, $sessionStorage, SweetAlert){
  var vm = this;
  $scope.vm.noEmail = false;
  $scope.$watch(function(){
    return UsersFactory.getUsersData();
  }, function(newValue, oldValue) {
    $scope.vm.noEmail = false;
    $scope.userData = UsersFactory.getUsersData();
    $scope.vm.name = $scope.userData.name;
    $scope.vm.email = $scope.userData.email;
    if($scope.userData.email){
      $scope.vm.noEmail = false;
      delete $scope.vm.username;
      delete $scope.vm.guardian_email;
      delete $scope.vm.phone;
    }else{
      $scope.vm.noEmail = true;
      delete $scope.vm.email;
      $scope.vm.username = $scope.userData.username;
      $scope.vm.guardian_email = $scope.userData.guardian_email;
      $scope.vm.phone = $scope.userData.phone;
    }

  });
  $scope.$watch(function(){
    return VendorsFactory.getVendorData();
  }, function(newValue, oldValue) {
    $scope.vendorDetails = VendorsFactory.getVendorData();
  });
  notify.config({
    duration: '3000'
  });


  vm.linkFb = linkFb;
  vm.update = updateProfile;

  $scope.openChnagePasswordModal = function () {
    var modalInstance = $uibModal.open({
      templateUrl: 'views/change_password_modal.html',
      controller: 'PasswordController',
      controllerAs: 'vm',
    });
  };

  function linkFb(){
    var response = FacebookFactory.login(function(data){
      if(!data.authResponse){
        notify({
          message: 'We are unable to process this request. Kindly login via Facebook.',
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
      }else{
        var request = {
          'fb_identifier':data.authResponse.userID
        };
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
                message: 'Your Facebook account linked successfully.',
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
    });
  }
  function updateProfile(){
    var request ;
    if(vm.noEmail){
      request = {
        'username':vm.username,
        'name':vm.name,
        'email' : null,
        'phone':vm.phone,
        'guardian_email':vm.guardian_email,
      };
    }else{
      request = {
        'name':vm.name,
        'email':vm.email,
        'username':null,
        'phone':vm.phone,
        'guardian_email':null
      };
    }
    if(request){
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
              message: 'Your profile updated successfully.',
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
});
Bountee.directive("passwordVerify", function() {
  return {
    require: "ngModel",
    scope: {
      passwordVerify: '='
    },
    link: function(scope, element, attrs, ctrl) {
      scope.$watch(function() {
        var combined;

        if (scope.passwordVerify || ctrl.$viewValue) {
          combined = scope.passwordVerify + '_' + ctrl.$viewValue;
        }
        return combined;
      }, function(value) {
        if (value) {
          ctrl.$parsers.unshift(function(viewValue) {
            var origin = scope.passwordVerify;
            if (origin !== viewValue) {
              ctrl.$setValidity("passwordVerify", false);
              return undefined;
            } else {
              ctrl.$setValidity("passwordVerify", true);
              return viewValue;
            }
          });
        }
      });
    }
  };
});
