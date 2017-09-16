/**
 * INSPINIA - Responsive Admin Theme
 *
 * Inspinia theme use AngularUI Router to manage routing and views
 * Each view are defined as state.
 * Initial there are written state for all view in theme.
 *
 */
 function config($stateProvider, $urlRouterProvider, $ocLazyLoadProvider, IdleProvider, KeepaliveProvider) {

    // Configure Idle settings
    IdleProvider.idle(5); // in seconds
    IdleProvider.timeout(120); // in seconds

    $urlRouterProvider.otherwise("/");

    $ocLazyLoadProvider.config({
        // Set to true if you want to see what and when is dynamically loaded
        debug: true
    });

    $stateProvider

    .state('home', {
        controller: 'UserController',
        controllerAs: 'vm',
        url: "/",
        templateUrl: "views/login.html",
        data: { pageTitle: 'Login', specialClass: 'gray-bg' }
    })

    .state('register', {
            controller: 'UserController',
            controllerAs: 'vm',
            url: "/register",
            templateUrl: "views/register.html",
            data: { pageTitle: 'Register', specialClass: 'gray-bg' },
            onEnter: function(VendorsFactory, $state){
                var vendor = VendorsFactory.getVendorData();
                var vendorSettings = {}
                if(typeof vendor != 'undefined'){

                  vendor.vendor_settings.map(function(item, index){
                    
                    if(item.setting_key.name != "Credit Type"){
                      vendorSettings[item.setting_key.name] = item.value * 1;
                    }

                  });

                  if(!vendorSettings['Patient Self Sign Up']){
                    $state.go('home');
                  } 
                }else{

                   $state.go('home'); 
                }
            }
    })
    .state('waysToEarn', {
        controller: 'UserController',
        controllerAs: 'vm',
        url: "/ways-to-earn",
        templateUrl: "views/ways-to-earn.html",
        data: { pageTitle: 'ways-to-earn', specialClass: 'gray-bg' }
    })

    .state('logout', {
        controller: 'UserController',
        controllerAs: 'vm',
        url: "/logout",
        templateUrl: "views/register.html",
        data: { pageTitle: 'Register', specialClass: 'gray-bg' }
    })
    .state('forgot_password', {
        url: "/forgot_password",
        templateUrl: "views/forgot_password.html",
        controller: 'UserController',
        controllerAs: 'vm',
        data: { pageTitle: 'Forgot password', specialClass: 'gray-bg' }
    })
    .state('trouble_logging_in', {
        url: "/trouble_logging_in",
        templateUrl: "views/trouble_logging_in.html",
        controller: 'UserController',
        controllerAs: 'vm',
        data: { pageTitle: 'Trouble Logging In?', specialClass: 'gray-bg' }
    })
    .state('user', {
        abstract: true,
        controller: 'UserController',
        controllerAs: 'vm',
        url: "/user",
        templateUrl: "views/common/content.html",
    })
    .state('user.dashboard', {
        url: "/dashboard",
        controller: 'DashboardController',
        controllerAs: 'vm',
        templateUrl: "views/dashboard.html",
        data: { pageTitle: 'Dashboard'}
    })

    // .state('user.cards', {
    //     url: "/myCards",
    //     controller: 'UserController',
    //     controllerAs: 'vm',
    //     templateUrl: "views/cards.html",
    //     data: { pageTitle: 'Dashboard'}
    // })

    .state('user.profile', {
        url: "/profile",
        templateUrl: "views/profile.html",
        controller: 'ProfileController',
        controllerAs: 'vm',
        data: { pageTitle: 'My Profile' }
    })
    .state('user.rewards', {
        url: "/my-rewards",
        controller: 'RedemptionController',
        controllerAs: 'vm',
        templateUrl: "views/rewards.html",
        data: { pageTitle: 'My Rewards' }
    })
    .state('user.redeem', {
        url: "/redeem-points",
        templateUrl: "views/redeem.html",
        controller: 'RedemptionController',
        controllerAs: 'vm',
        data: { pageTitle: 'Redeem Points' }
    })
    .state('user.activities', {
        url: "/activities",
        templateUrl: "views/activities.html",
         controller: 'ActivitiesController',
        controllerAs: 'vm',
        data: { pageTitle: 'My Activies' }
    })
    .state('user.referral', {
        url: "/referral",
        templateUrl: "views/referral.html",
         controller: 'ReferralController',
        controllerAs: 'vm',
        data: { pageTitle: 'Referrals' },
        onEnter: function(VendorsFactory, $state){
            var vendor = VendorsFactory.getVendorData();
            var vendorSettings = {}
            if(typeof vendor != 'undefined'){

              vendor.vendor_settings.map(function(item, index){
                
                if(item.setting_key.name != "Credit Type"){
                  vendorSettings[item.setting_key.name] = item.value * 1;
                }

              });

              if(!vendorSettings['Referrals']){
                $state.go('user.dashboard');
              } 
            }else{

               $state.go('home'); 
            }
        }
    })
    .state('user.event', {
        url: "/event",
        templateUrl: "views/event.html",
        controller: 'EventController',
        controllerAs: 'vm',
        data: { pageTitle: 'Managing Emails' }
    })
    .state('reset_password', {
    controller: 'UserController',
    controllerAs: 'vm',
    url: "/reset_password?resetToken",
    templateUrl: "views/reset_password.html",
    data: { pageTitle: 'Forgot password', specialClass: 'gray-bg' }
    })
    
}
angular
.module('inspinia')
.config(config)
.run(function($rootScope, $state) {
    $rootScope.$state = $state;
});
