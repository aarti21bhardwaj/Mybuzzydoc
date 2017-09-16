//$provide.factory('apiInterceptor', );


dashboardApp.factory('apiInterceptor', function apiInterceptor($q, $window) {
  return {
    request: function(config) {
    //	console.log(config);
    config.headers['accept'] = 'application/json';
    config.headers['X-Requested-With'] = config.url;
    config.headers['content-type'] = 'application/json';

      return config;
    },

    requestError: function(config) {
      return config;
    },

    response: function(res) {
      return res;
    },

    responseError: function(res) {
      if(res.status == 403){

        $window.location.href = $window.host + 'users/logout';
      }
      return $q.reject(res);
    }
  }
});
