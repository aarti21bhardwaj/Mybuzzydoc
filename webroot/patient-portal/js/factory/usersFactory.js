Bountee.factory('UsersFactory', function($http,$sessionStorage,$cookies,buzzyEnv,phid,mode,vid,bounteeSandbox,bounteeLive,VendorsFactory,$state) {
  var factory = {};


  var host = bounteeSandbox;
  var buzzy_host = buzzyEnv;

  if(mode != 0){
    host = bounteeLive;
  }
  factory.usersData = false;

  factory.getUsersData = function(){
    return factory.usersData;
  }

  factory.login = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    // var authString = $base64.encode(request.username+':'+request.password);
    var req = {
      'username':request.username,
      'password':request.password
    };
    $sessionStorage.$reset();
    var req = {
      method: 'POST',
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/loginPatient",
      headers: {
        'mode': mode
      },
      data: req
    };

    $http(req).then(function(successCallback) {
      if(successCallback.data.status){
        $sessionStorage.$default({'u_t': successCallback.data.data.token});
        $sessionStorage.$default({'u_r_t': successCallback.data.data.refresh_token});

        factory.token = successCallback.data.data.token;
        factory.refreshKey = successCallback.data.data.refresh_token;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }


  factory.switchAccount = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';

    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'POST',
      // url: host + "/api/user/switch_account",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/switchAccount",

      data: request,
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      }
    };
    $http(req).then(function(successCallback) {
      if(successCallback.data.status){
        $sessionStorage.$reset();
        $sessionStorage.$default({'u_t': successCallback.data.data.token});
        factory.token = successCallback.data.data.token;
      }else{
        delete $sessionStorage.u_t;
        delete $sessionStorage.u_r_t;       
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }




  factory.forgotPassword = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    $sessionStorage.$reset();
    var req = {
      method: 'POST',
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/forgotPassword",
      headers: {
        'mode': mode
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }
  factory.resetPassword = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';

    $sessionStorage.$reset();
    var req = {
      method: 'POST',
      // url: host + "/api/user/reset_password",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/resetPassword",
      headers: {
        'mode': mode
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }

  factory.fbLogin = function(token) {
    $sessionStorage.$default({'u_t': token});
    factory.token = token;
    factory.getMyInfo(function(err,data){
      if (err) {

      }else{
         $state.go('user.dashboard') ;

      }
    });
  }

  factory.referral = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var req = {
      method: 'POST',
      // url: buzzy_host + "/patient_portal_apis/Referrals/",
      url : buzzy_host + "/integrateideas/peoplehub/api/patients/referral",
      headers: {
        'Content-Type': 'application/json',
        'mode' : mode
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }

  factory.getMyInfo = function(callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var vendorDetails = VendorsFactory.getVendorData();
    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'GET',
      // url: host + "/api/user/me?vendor_id="+phid,
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/getPatientInfo/"+phid,
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      }
    };

    $http(req).then(function(successCallback) {
      if(successCallback.data.status){
        factory.usersData = successCallback.data.data;
        factory.getReviewLink();
      }else{
        console.log('when successCallback data is false');
        delete $sessionStorage.u_t;
        delete $sessionStorage.u_r_t;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      console.log('in error');
      console.log(errorCallback);
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });
  }

  factory.getReviewLink = function(){
    
    var data = {
      vendorId: vid,
      patientId: factory.usersData.id
    }

    if(typeof factory.usersData.email != "undefined" && factory.usersData.email != "" && factory.usersData.email != null){
      data.email = factory.usersData.email;
    }else{
      data.email = factory.usersData.guardian_email;
    }

    var req = {
      method: 'POST',
      url: buzzy_host + "/patient_portal_apis/users/getReviewLink",
      data: data
    }
    
    $http(req).then(function(response) {

      factory.usersData.reviewLink = response.data.reviewUrl;
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    
    }, function(response) {

      console.log(response);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    
    });

  }


  factory.myActivities = function(callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'GET',
      // url: host + "/api/vendor/activities",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/getPatientActivities",
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      }
    };

    $http(req).then(function(successCallback) {
      if(!successCallback.data.status){
        delete $sessionStorage.u_t;
        delete $sessionStorage.u_r_t;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });
  }

  factory.rewardFbPageLikePromotionPoints = function(request,callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var req = {
      method: 'POST',
      url: buzzy_host + "/patient_portal_apis/Awards/promotions",
      headers: {
        'mode': mode
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });
  }

  factory.registerMe = function(request,callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    request.vendor_id = vid;
    request.vendor_peopleHub_id = phid;
    request.vendor_mode = mode;
    var req = {
      method: 'POST',
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/registerPatient",
      headers: {
        'mode': mode
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });
  }
  factory.updateMe = function(request,callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'PUT',
      // url: host + "/api/user/users/"+factory.usersData.id,
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/editPatient/"+factory.usersData.id,
      data: request,
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      }
    };

    $http(req).then(function(successCallback) {
      if(successCallback.data.status){
        var token = $sessionStorage.u_t;
        var refreshToken = $sessionStorage.u_r_t;
        var req = {
          method: 'GET',
          // url: host + "/api/user/me?vendor_id="+phid,
          url: buzzy_host + "/integrateideas/peoplehub/api/patients/getPatientInfo/"+phid,
          headers: {
            'mode' : mode,
            'Authorization': 'Bearer ' +token,
            'r_t' : refreshToken
          }
        };

        $http(req).then(function(successCallbackData) {
          if(successCallback.data.status){
            factory.usersData = successCallbackData.data.data;
          }else{
            delete $sessionStorage.u_t;
            delete $sessionStorage.u_r_t;
 
          }
        }, function(errorCallback) {
        });
      }else{
          delete $sessionStorage.u_t;
          delete $sessionStorage.u_r_t;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });
  }
  factory.redeemWalletPoints = function(request, callback) {
    var vendorDetails = VendorsFactory.getVendorData();
    var loading = document.getElementById('loading-bar');
    var phRedemptionData = request;
    phRedemptionData['ref'] = buzzy_host;
    phRedemptionData['vendor_id'] = phid;
    var legacyRedemptionData = {
      redeemer_peoplehub_identifier : factory.usersData.id,
      redeemer_name : factory.usersData.name,
      points :  factory.usersData.totalWalletCredits,
      vendor_id: vid
    };

    request = {
      phRedemptionData : phRedemptionData,
      legacyRedemptionData : legacyRedemptionData
    };
    
    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'POST',
      // url: host + "/api/user/RedeemedCredits",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/redeemedCredits",
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      },
      data: request
    };

    $http(req).then(function(successCallback) {
      if(!successCallback.data.status){
        delete $sessionStorage.u_t;
        delete $sessionStorage.u_r_t;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }
  factory.redeemProduct = function(request, callback) {
    
    var loading = document.getElementById('loading-bar');
    request['vendor_id'] = vid;
    request['redeemer_peoplehub_identifier'] = factory.usersData.id;
    request['redeemer_name'] = factory.usersData.name;
    loading.style.display = 'block';
    var token = $sessionStorage.u_t;
    var req = {
      method: 'POST',
      // url: host + "/api/user/RedeemedCredits",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/redeemProduct",
      data: request
    };

    $http(req).then(function(successCallback) {
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }

  factory.addCard = function(request, callback) {
    var loading = document.getElementById('loading-bar');
    loading.style.display = 'block';
    var token = $sessionStorage.u_t;
    var refreshToken = $sessionStorage.u_r_t;
    var req = {
      method: 'POST',
      // url: host + "/api/user/userCards",
      url: buzzy_host + "/integrateideas/peoplehub/api/patients/addPatientCard",
      data: request,
      headers: {
        'mode' : mode,
        'Authorization': 'Bearer ' +token,
        'r_t' : refreshToken
      },
    };

    $http(req).then(function(successCallback) {
      if(!successCallback.data.status){
        delete $sessionStorage.u_t;
        delete $successCallback.u_r_t;
      }
      callback(null, successCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    }, function(errorCallback) {
      callback(errorCallback.data);
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'none';
    });

  }
  return factory;
});