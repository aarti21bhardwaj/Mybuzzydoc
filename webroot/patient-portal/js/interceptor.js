//$provide.factory('apiInterceptor', );


Bountee.factory('apiInterceptor', ['$cookies', function apiInterceptor($cookies) {
  return {
    request: function(config) {
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
    }
  }
}])
