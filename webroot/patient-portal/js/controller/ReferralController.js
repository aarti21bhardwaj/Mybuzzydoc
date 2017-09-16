Bountee.controller('ReferralController', function($rootScope,$location, $state, $window,notify,$compile, $scope, $http, UsersFactory,VendorsFactory, $sessionStorage, SweetAlert){

  var vm = this;
  $scope.$watch(function(){
    return UsersFactory.getUsersData();
  }, function(newValue, oldValue) {
    $scope.userData = UsersFactory.getUsersData();
  });
  var vendorDetails = null;
  $scope.$watch(function(){
    return VendorsFactory.getVendorData();
  }, function(newValue, oldValue) {
    $scope.vendorDetails = vendorDetails =VendorsFactory.getVendorData();
  });

  notify.config({
    duration: '3000'
  });

  $scope.selectTemplate = function() {
    if (!$scope.selectedTemplate) {
      $scope.vm.subject = '';
      $scope.vm.description = '';
    }else{
      for (var x in vendorDetails.referral_templates) {
        if (vendorDetails.referral_templates[x].id == $scope.selectedTemplate) {
          console.log(vendorDetails.referral_templates[x]);
          $scope.vm.subject = vendorDetails.referral_templates[x].subject.replace(/\+/g,' ');
          $scope.vm.description = vendorDetails.referral_templates[x].description.replace(/\+/g,' ');
          break;
        }
      }
    }
  }

  vm.refer = refer;

  function refer() {
    var userData = UsersFactory.getUsersData();
    email = userData.email;
    if(!email){
      email = userData.guardian_email;
    }
    var request = {
      'first_name':vm.first_name,
      'last_name':vm.last_name,
      'phone':vm.phone,
      'refer_to':vm.refer_to,
      'refer_from': email,
      'status':1,
      'peoplehub_identifier': userData.id,
      'subject':vm.subject,
      'vendor_id':vendorDetails.id,
      'description':vm.description
    }
    UsersFactory.referral(request,function(err,data){
      if (err) {
        notify({
          message: err.message,
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
        $scope.customAlert = true;
      } else {
        swal({
          title:"Congratulations!!",
          text: "You have referred "+request.first_name+" successfully",
          type: "success",
          showCancelButton: false,
          timer: 4000
        });
      }
    });
  }

});
