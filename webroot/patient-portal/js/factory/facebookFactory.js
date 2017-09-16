Bountee.factory('FacebookFactory', function($q, UsersFactory, $window, buzzyEnv, $http, mode, phid,$sessionStorage) {
  var factory = {};
  factory.getFbPageLikeStatus = function(pageUrl,callback) {
    var factoryRes = {};
    FB.login(function(response) {
      if(response.authResponse){
        FB.api("/",{
          "id": pageUrl
        },function (res) {
          console.log(res);
          if(res && !res.error){
            FB.api('/me/likes?target_id='+res.id,function(response) {
              console.log(response);
              if (!response || response.error || (response && !response.data.length)) {
                factoryRes.status = false;
                callback(factoryRes);
              } else {
                factoryRes.status = true;
                callback(factoryRes);
              }
            }, {scope: 'user_likes'});
          }
        });
      }else{
        callback(false);
      }
    }, {scope: 'email,user_likes'});
  }

  factory.login = function() {
     
     return $window.open(buzzyEnv+"/integrateideas/peoplehub/api/patients/loginSocialUser?provider=Facebook&vendor_id="+phid+"&mode="+mode,"width=600,height=400", "facebookIframe");
  }

  factory.getAccessToken = function(token){

    var req = {
      method: 'POST',
      url:  buzzyEnv+"/integrateideas/peoplehub/api/patients/validateSocialLogin",
      headers: {
        'mode' : mode,
        'Authorization' :token
      }
    };
    return $http(req);
  }

  factory.signUp = function(cardNumber) {
    if (typeof(cardNumber)==='undefined') cardNumber = '';
      return $window.open(buzzyEnv+"/integrateideas/peoplehub/api/patients/registerSocialUser?provider=Facebook&vendor_id="+phid+"&card_number="+cardNumber+"&mode="+mode,"width=600,height=400", "facebookIframe");
  }

 factory.linkFb = function() {
     return $window.open(buzzyEnv+"/integrateideas/peoplehub/api/patients/LinkSocialAccount?provider=Facebook&vendor_id="+phid+"&mode="+mode+"&token="+$sessionStorage.u_t,"width=600,height=400", "facebookIframe");
  }
  factory.getFbPageId = function(url, callback) {
    FB.login(function(response) {
      if(response.authResponse){
        FB.api("/",{
          "id": url
        },function (response) {
          callback(response);
        });
      }else{
        callback(false);
      }
    });
  }



  return factory;
});
