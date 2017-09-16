Bountee.controller('UserController', function($rootScope,$location,$state,$timeout,$window, $scope,notify, $http,UsersFactory,buzzyEnv,VendorsFactory,FacebookFactory,$sessionStorage,$localStorage,SweetAlert, $stateParams){
  $scope.numRegex = "\\d+";
  $scope.loginFailed = false;
  $scope.forgotAttributes = [
    {
      value : "card",
      label : "Card Number"
    },
    {
      value : "email",
      label : "Email"
    },
    {
      value : "username",
      label : "Username"
    }
  ];

  var vm = this;
  getVendorDocuments();
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

  $scope.sideNavLogo = buzzyEnv + '/img/icon-reverse-low-rez.png';


  function getVendorDocuments(){

    VendorsFactory.getVendorDocuments().then(function(response){

     console.log(response);
     $scope.vendorDocuments =  response.data.response;
     console.log($scope.vendorDocuments);

   }, function(response){

    $scope.vendorDocuments = false;

  });

  }


  vm.login = login;
  vm.logout = logout;
  vm.fbLogin = fbLogin;
  vm.fbSignUp = fbSignUp;
  vm.register = register;
  vm.resetPassword = resetPassword;
  vm.forgot_password = forgotPassword;
  vm.addCard = addCard;
  vm.switchAccount = switchAccount; 

  notify.config({
    duration: '3000'
  });

  function logout() {
    $sessionStorage.$reset();
    // $localStorage.$reset`();
    notify({
      message: 'You are logged Out Successfully.',
      classes: 'alert-success',
      templateUrl: 'views/common/custom-notify-success.html'
    });
    $scope.customAlert = true
    $state.go('home');
  } //end function

  function login() {
    var request = {
      'username':vm.username,
      'password':vm.password
    }
    UsersFactory.login(request,function(err,data){
      if (err) {
        notify({
          message: err.message,
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
        $scope.loginFailed = true;
        $scope.customAlert = true;
      } else {
        if(!angular.isUndefined(data) && data.status){
          // console.log(UsersFactory.factory.token);
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
            } else {
              console.log(' i m here');
              $state.go('user.dashboard') ;
            }
          });
        }else{
          notify({
            message: 'Something Went wrong. Please try after sometime',
            classes: 'alert-danger',
            templateUrl: 'views/common/custom-notify-danger.html'
          });
          $scope.loginFailed = true;
          $scope.customAlert = true;
        }

      }

    });
  }//end function

  function fbLogin(){
    var response = FacebookFactory.login();
    var eventMethod = $window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = $window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    eventer(messageEvent,function(e) {
      response.close();
      if(e.data.token !='linked'){
      FacebookFactory.getAccessToken(e.data.token).then(function(res){
        UsersFactory.fbLogin(res.data.token);
      },function(res){
        console.log(res);
      }); 
      }
               
    }, false);
  }



  function fbSignUp(){
    var cardNumber = $scope.card_number;
    var response = FacebookFactory.signUp(cardNumber);
    var eventMethod = $window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = $window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    eventer(messageEvent,function(e) {
      response.close();
      if(e.data.token !='linked'){
       FacebookFactory.getAccessToken(e.data.token,cardNumber).then(function(res){
        UsersFactory.fbLogin(res.data.token);
      },function(res){
        console.log(res);
      });
      }
                
    }, false);
  }//END Function

  function registerUser(request, isFbSignUp){

    if(typeof isFbSignUp == 'undefined' || isFbSignUp == ''){
      isFbSignUp = false;
    }

    var a = UsersFactory.registerMe(request,function(err,data){
      if (err) {
        swal({
          title: "Sorry!! Unable to process your request",
          text: err.message,
          type: "error",
          timer: 6000,
          confirmButtonText: "Let's Try Again"
        });
        $sessionStorage.$reset();
      } else {
        $sessionStorage.$reset();
        if(data.status){
          if(isFbSignUp){
            var req = {
              'fbId':request.fb_identifier
            };
            var a = UsersFactory.fbLogin(req,function(err,data){
              if (err) {
                notify({
                  message: err.message,
                  classes: 'alert-danger',
                  templateUrl: 'views/common/custom-notify-danger.html'
                });
                $scope.customAlert = true;
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
                  } else {
                    $state.go('user.dashboard') ;
                  }
                });
              }
            });
          }else{
           swal({
              title:"Congratulations!!",
              text: "You are successfully registered.",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: '#5cb85c',
              confirmButtonText: "Let's Login",
            },
            function(isConfirm){
              $state.go('home') ;
            });
          }

        }else{
          swal({
            title: data.title,
            text: data.message,
            type: "error",
            timer: 4000,
            confirmButtonText: "Lets Try Again"
          });
        }

      }

    });
  }

  function register() {
    if(vm.noEmail){
      var request = {
        'username':vm.username,
        'first_name':vm.first_name,
        'last_name':vm.last_name,
        'password':vm.password,
        'phone':vm.phone,
        'guardian_email':vm.guardian_email
      };
    }else{
      var request = {
        'first_name':vm.first_name,
        'last_name':vm.last_name,
        'password':vm.password,
        'email':vm.email
      };
    }
    if(vm.card_number){
      request.card_number=vm.card_number;
    }

    registerUser(request);
  }//end function

  function resetPassword(){

    var request = {
      'new_password':vm.new_password,
      'cnf_password':vm.cnf_password,
      'reset-token':$stateParams.resetToken
    };
    UsersFactory.resetPassword(request,function(err,data){
      if (err) {
        notify({
          message: err.message,
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
        $scope.customAlert = true;
      } else {
        SweetAlert.swal({
          title:"Congratulations!!",
          text: "Password changes successfully.",
          type: "success",
          showCancelButton: false,
          confirmButtonColor: '#5cb85c',
          confirmButtonText: "Let's Login",
        },
        function(isConfirm){
          $state.go('home') ;
        });
      }
    });
  }

  function forgotPassword(){

    console.log($location);
    var request = {
      'value':vm.attribute_value,
      'ref':buzzyEnv+'/patient-portal/'+$scope.vendorDetails.id+'#/',
      'vendor_id': $scope.vendorDetails.id,
      'attribute_type' : vm.attribute_type
    };
    // console.log($scope.vendorDetails);
    UsersFactory.forgotPassword(request,function(err,data){
      if (err) {
        notify({
          message: err.message,
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
        $scope.customAlert = true;
      } else {
        SweetAlert.swal({
          title:"Congratulations!!",
          text: "We have sent a reset password Link to your email id. Kindly check your mail",
          type: "success",
          showCancelButton: false,
          confirmButtonColor: '#5cb85c',
          confirmButtonText: "Let's Login",
        },
        function(isConfirm){
          $state.go('home') ;
        });
      }
    });
  }
  function addCard(){
    var request = {
      'card_number':vm.card_number,
      'status':1,
    };
    console.log(request);
    UsersFactory.addCard(request,function(err,data){
      if (err) {
        for(var x in err.error){
          notify({
            message: err.error[x],
            classes: 'alert-danger',
            templateUrl: 'views/common/custom-notify-danger.html'
          });  
        }
        
        $scope.customAlert = true;
      } else {
        SweetAlert.swal({
          title:"Congratulations!!",
          text: "We have added your card successfully",
          type: "success",
          showCancelButton: false,
          confirmButtonColor: '#5cb85c',
          confirmButtonText: "Ok",
        },
        function(isConfirm){
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
        });
      }
    });
  }

function switchAccount(e){
    var id = $(e.target).data('id');
    var request = {
      'account_id':id
    }
    UsersFactory.switchAccount(request,function(err,data){
      if (err) {
        notify({
          message: err.message,
          classes: 'alert-danger',
          templateUrl: 'views/common/custom-notify-danger.html'
        });
        $scope.customAlert = true;
      } else {
        if(!angular.isUndefined(data) && data.status){
          // console.log(UsersFactory.factory.token);
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
            } else {
              console.log(' i m here');
              $state.go('user.dashboard') ;
            }
          });
        }else{
          notify({
            message: 'Something Went wrong. Please try after sometime',
            classes: 'alert-danger',
            templateUrl: 'views/common/custom-notify-danger.html'
        });
             
          $scope.customAlert = true;
        }

      }

    });


  }


});
