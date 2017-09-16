//Creating the SearchResults Factory for caching the search results
dashboardApp.factory('SearchResults', [function searchResultsFactory(){
	//Returning an object
	return {
		data: {},
		update: function(obj) {
			this.data = obj;
		}
	}
}]);