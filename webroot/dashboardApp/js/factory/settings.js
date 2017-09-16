//Creating the Settings Factory
dashboardApp.factory('Settings', [function settingsFactory(){
	//Returning an object
	return {

		settingfeatures:{},

		 update: function(sfeatures) {
		  	for(ft in sfeatures){
		  		//console.log(sfeatures);
		  		this.settingfeatures[(sfeatures[ft].setting_key.name).replace(/ /g,"_").toLowerCase()] = sfeatures[ft].value;
		  	}
		  	console.log(this.settingfeatures);
		}
		
	}
}]);