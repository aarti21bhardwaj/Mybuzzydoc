Bountee.controller('RedemptionController', function($location, $state, $window, $scope, $http, UsersFactory,VendorsFactory, $sessionStorage, SweetAlert, buzzyEnv){
  var vm = this;
  $scope.buzzy_host = buzzyEnv;
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


  function redeem(request, redeemMethod){

    redeemMethod(request,function(err,data){
      if (err) {
        swal({
          title: "Sorry! We are unable to process your request.",
          text: err.message,
          type: "error",
          timer: 4000,
          confirmButtonText: "Let's Try Again."
        });
        console.log(err);
      } else {
        UsersFactory.getMyInfo(function(err,data){
          if (err) {
            $sessionStorage.$reset();
            $state.go('home') ;
            notify({
              message: err.message,
              classes: 'alert-danger',
              templateUrl: 'views/common/custom-notify-danger.html'
            });
            $scope.customAlert = true;
         }
      });
        if(data.status){
          swal({
            title:"Congratulations!!",
            text: "Points Redeemed Successfully.",
            type: "success",
            showCancelButton: false,
            timer: 4000
          });
        }else{
          swal({
            title: "Sorry! We are unable to process your request.",
            text: 'Kindly try again after some time.',
            type: "error",
            timer: 4000,
            confirmButtonText: "Let's Try Again."
          });
        }
      }


    });
  }


  vm.confirmRedeem = function confirmRedeem(redeemType, service){
    
    if(redeemType == 'product'){
      var message = "You want to redeem "+service.points+" points for "+service.name;
      var request = {
        'legacy_reward_id':service.id,
      }
      redeemMethod = UsersFactory['redeemProduct'];

    }else{
      var message = "You want to redeem all of your credits as "+service+" gift card.";
      var request = {
      'service':service,
      'reward_type':'wallet_credit'
      }
      redeemMethod = UsersFactory['redeemWalletPoints'];
    }



    swal({
      title: "Are you sure?",
      text: message,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Yes, redeem!',
      closeOnConfirm: true
    },
    function(){
      redeem(request, redeemMethod);
    });
  }


});
