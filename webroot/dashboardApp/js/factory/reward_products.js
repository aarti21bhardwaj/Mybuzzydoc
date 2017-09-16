//Creating the RewardProductsFactory Factory for caching the Rewards results
dashboardApp.factory('RewardProducts', ['$http', 'apiHost', function rewardProductsFactory($http, apiHost){
	//Returning an object
	return {
		data: false,
		loadData: function(x) {

			if(typeof x == 'undefined' || !x){
				x = 1;
			}

				return $http.get(apiHost+'api/legacyRewards/');
				;
			},
		update: function(obj){
			this.data = obj;
		}
	}
}]);