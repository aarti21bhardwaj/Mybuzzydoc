Bountee.factory('VendorsFactory', function($http,$sessionStorage,$localStorage,$cookies,buzzyEnv,vid,mode,bounteeSandbox,bounteeLive) {
  var factory = {};


  var host = buzzyEnv;
  var buzzy_host = buzzyEnv;
  if(mode != 0){
    host = bounteeLive;
  }
  factory.getVendorData = function(){
    return $localStorage.v_det;
  }

  factory.fetchVendorData = function(callback) {
  
    var vendorId = vid;
    if(!vendorId){
      notify({
        message: 'Invalid Request.',
        classes: 'alert-danger',
        templateUrl: 'views/common/custom-notify-danger.html'
      });
    }else{
      var loading = document.getElementById('loading-bar');
      loading.style.display = 'block';
      var req = {
        method: 'GET',
        // url: buzzy_host + "/patient_portal_apis/Vendors/"+vendorId
        url: buzzy_host + "/integrateideas/peoplehub/api/patients/viewVendor/"+vendorId
      };

      $http(req).then(function(successCallback){
        console.log(successCallback.data);
        // $logo = successCallback.data.vendor.image_url.split("/");
        $logo = successCallback.data.image_url.split("/");
        $logo = $logo[$logo.length-1];
        if($logo == "default-img.jpeg"){
          // successCallback.data.vendor.image_url = buzzy_host+"/img/icon-low-rez.png";
          successCallback.data.image_url = buzzy_host+"/img/icon-low-rez.png";
        }
        $localStorage.$reset();
        callback(null,successCallback.data);
        // $localStorage.$default({'v_det': successCallback.data.vendor});
        $localStorage.$default({'v_det': successCallback.data});
        // factory.vendorData = successCallback.data.vendor;
        loading.style.display = 'none';
      }, function(errorCallback) {
        callback(errorCallback.data);
        loading.style.display = 'none';
      });
    }
  }

  factory.getVendorDocuments = function(){

    return $http.get(buzzy_host + '/api/VendorDocuments/viewVendorDocuments/' + vid);

  }

  return factory;
});
