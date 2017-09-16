Bountee.controller('DashboardController', function($location, $state, $timeout,$window, $scope, $http,notify, UsersFactory,VendorsFactory,FacebookFactory, $sessionStorage, SweetAlert){
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
    if(typeof $scope.vendorDetails != "undefined"){

      $scope.vendorSettings = {}
      $scope.vendorDetails.vendor_settings.map(function(item, index){
          
          if(item.setting_key.name != "Credit Type"){
            $scope.vendorSettings[item.setting_key.name] = item.value * 1;
          }

      });
    }
  });


  notify.config({
    duration: '3000'
  });
  vm.LinkFb = LinkFb;
  // vm.clamePageLikeReward = clamePageLikeReward;
  //
  // function clamePageLikeReward(){
  //   if(vendorDetails.vendor_locations.length && vendorDetails.vendor_locations[0].fb_url){
  //     FacebookFactory.getFbPageLikeStatus(vendorDetails.vendor_locations[0].fb_url, function(data){
  //       if(data){
  //         if(data.status){
  //           var request = {
  //             'promotion_id' : '6',
  //             'attribute_type' : 'email',
  //             'attribute' : 'nitesh.srivastava@twinspark.co',
  //             'vendor_id': vendorDetails.id
  //           };
  //           UsersFactory.rewardFbPageLikePromotionPoints(request,function(err,data){
  //             if (err) {
  //               notify({
  //                 message: err.message,
  //                 classes: 'alert-danger',
  //                 templateUrl: 'views/common/custom-notify-danger.html'
  //               });
  //               $scope.customAlert = true;
  //             } else {
  //               swal({
  //                 title:"Congratulations!!",
  //                 text: "You got "+ data.response.points+" reward points.",
  //                 type: "success",
  //                 showCancelButton: false,
  //                 timer: 4000
  //               });
  //             }
  //           });
  //         }else{
  //           notify({
  //             message: 'Kindly "Like" the facebook page to claim reward..',
  //             classes: 'alert-danger',
  //             templateUrl: 'views/common/custom-notify-danger.html'
  //           });
  //         }
  //       }else{
  //         notify({
  //           message: 'We are unable to fetch details from facebook. Kindly Login to facebook to claim reward.',
  //           classes: 'alert-danger',
  //           templateUrl: 'views/common/custom-notify-danger.html'
  //         });
  //       }
  //     });
  //   }
  // }

function LinkFb(){
    var response = FacebookFactory.linkFb();
     var eventMethod = $window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = $window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    eventer(messageEvent,function(e) {
      response.close();
      if(e.data.token =='linked'){
        swal({
            title:"Congratulations!!",
              text: 'Your account is successfully linked with Facebook',
              type: "success",
              showCancelButton: false,
              //confirmButtonColor: '#5cb85c',
              confirmButtonText: false
          });
      } 
      //alert('linked');
          

    }, false);
  }//END Function


  /*function linkFb(){
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
  }*/

});
