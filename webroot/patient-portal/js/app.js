var Bountee =  angular.module("inspinia",['ui.router',                    // Routing
'oc.lazyLoad',                  // ocLazyLoad
'ui.bootstrap',
'ui.switchery',                 // Ui Bootstrap
'pascalprecht.translate',       // Angular Translate
'ngIdle',                       // Idle timer
'ngSanitize',
'ngCookies',
'ngStorage',
'angular-loading-bar',
'oitozero.ngSweetAlert',
'yaru22.angular-timeago',
'cgNotify',
'socialLogin'
])
.config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
  cfpLoadingBarProvider.includeBar = false;
  cfpLoadingBarProvider.spinnerTemplate = '<div id="loading-bar-container"><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div><div class="sk-rect2"></div><div class="sk-rect3"></div><div class="sk-rect4"></div><div class="sk-rect5"></div></div></div>';
}])
.config(function(socialProvider){
  socialProvider.setFbKey({
    appId: "1781323288806172",
    apiVersion: "v2.8"
  });
}).run(['$rootScope', '$window','UsersFactory','VendorsFactory','$state','$sessionStorage','$localStorage','notify',function($rootScope, $window,UsersFactory,VendorsFactory,$state,$sessionStorage,$localStorage,notify) {
  if(!angular.isUndefined($localStorage.v_det) && ($localStorage.v_det.id != document.getElementById('b-v').value)){    
   $localStorage.$reset();
   $sessionStorage.$reset();
   $state.go('home') ;
 }
 if(angular.isUndefined($sessionStorage.u_t) && !angular.isUndefined($localStorage.v_det)){
   $localStorage.$reset();
 }
 if(VendorsFactory.getVendorData()){
  var d = VendorsFactory.getVendorData();
  if(d.id != document.getElementById('b-v').value){
    $localStorage.$reset();
    $sessionStorage.$reset();
    $state.go('home') ;
  }
}
if(!VendorsFactory.getVendorData()){
  VendorsFactory.fetchVendorData(function(err,data){
    if (err) {
      $sessionStorage.$reset();
      $state.go('home') ;
      notify({
        message: err.message,
        classes: 'alert-danger',
        templateUrl: 'views/common/custom-notify-danger.html'
      });
    }
  });
}
$window.fbAsyncInit = function() {
  FB.init({
    appId: '1781323288806172',
    status: true,
    cookie: true,
    xfbml: true,
    version: 'v2.8'
  });
};

$rootScope.$on('$stateChangeSuccess',function(event, toState, toParams, fromState, fromParams) {
  if($state.current.name == "user.event" || $state.current.name == "user.dashboard" || $state.current.name =="user.profile" || $state.current.name =="user.rewards" || $state.current.name =="user.redeem" || $state.current.name =="user.activities" || $state.current.name =="user.referral" || $state.current.name =="user.cards"){
    if(!angular.isUndefined($sessionStorage.u_t)){
      if(!UsersFactory.getUsersData()){
        UsersFactory.getMyInfo(function(err,data){
          if (err) {
            $sessionStorage.$reset();
            $state.go('home') ;
          }
        });
      }
    }else{
      $sessionStorage.$reset();
      $state.go('home') ;
    }
  }

  if($state.current.name == "home" || $state.current.name == "register" || $state.current.name == "waysToEarn"){
    if(!angular.isUndefined($sessionStorage.u_t)){
      if(UsersFactory.getUsersData()){
        $state.go('user.dashboard');
      }else{
        UsersFactory.getMyInfo(function(err,data){
          if (err) {
            $sessionStorage.$reset();
          }
        });
      }
    }else{
      $sessionStorage.$reset();
    }
  }
});
}]);

var env = {};

// Import variables if present (from env.js)

if(window){
  env = window.host;
}

Bountee.constant('phid', document.getElementById('r-v').value);
Bountee.constant('vid', document.getElementById('b-v').value);
Bountee.constant('mode', document.getElementById('v-m').value);
Bountee.constant('bounteeSandbox', 'http://peoplehub.twinspark.co/bountee_dev');
Bountee.constant('bounteeLive', 'http://peoplehub.twinspark.co/bountee_staging');
Bountee.constant('buzzyEnv', 'http://localhost/buzzyadmin');
// Bountee.config(function($httpProvider){
//     $httpProvider.interceptors.push('apiInterceptor');
// });
