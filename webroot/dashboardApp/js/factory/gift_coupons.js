//Creating the RewardProductsFactory Factory for caching the Rewards results
dashboardApp.factory('GiftCoupons', ['$http', 'apiHost', function giftCouponsFactory($http, apiHost){
	//Returning an object
	return {
		vendorCoupons: false,
		vendorInstCoupons:false,
		loadVendorCoupons: function() {
			return $http.get(apiHost+'api/giftCoupons/getVendorsCoupons/1');
		},
		updateVendorCoupons: function(obj){
			this.vendorCoupons = obj;
		},
		loadVendorInstantCoupons: function(){
			return $http.get(apiHost+'api/giftCoupons/getVendorsCoupons/2')
		},
		updateVendorInstantCoupons: function(obj){
			this.vendorInstCoupons = obj;
		}
	}
}]);