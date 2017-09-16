//Creating the Vendor Factory for caching the Vendor 
dashboardApp.factory('Vendor', ['$http', 'apiHost', function VendorFactory($http, apiHost){
	//Returning an object
	return {
		data: false,
		loggedInUserId:false,
		surveySwiper:false,
		update: function(obj) {
			obj.vendor_referral_settings.push({

				id: obj.vendor_referral_settings.length,
				referral_level_name: "Not Ready Yet",
				status:1

			});
			this.data = obj;
			if(obj != false){

				this.loggedInUserId = this.setLoggedInUser();
			}
			
        },
		loadData: function(x) {
			
			if(typeof x == 'undefined' || !x){
				x = 1;
			}

			return $http.get(apiHost+'api/vendors/'+x);
		},
		setLoggedInUser: function(){

			if(this.data.users.length == 1){

				return this.data.users[0].id;

			}else{

				loggedInUserId = $.cookie("username");

				if(typeof loggedInUserId != "undefined" && loggedInUserId){
						this.data.users.map(function(item, index){
							if(loggedInUserId == item.username){

								loggedInUserId = item.id;
							}
						});
						return loggedInUserId 
				}else{
					return this.data.users[0].id;
				}


				
			}


					
			
		}
	}
}]);