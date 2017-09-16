dashboardApp.directive("vpsurveyradio", function(){
	return{
		restrict: 'E',
		scope:{
			options : '=',
			promotion : '=',
			setFavorite: '&',
		},
		template: '<span ng-repeat = "x in options.data"><div class="radio"><input type="radio" name="vp{{promotion.id}}" ng-model="selectedoption" ng-click = "setFavorite(x)" ng-value="{{x.id}}"><label></label> {{x.value}}</div></span>{{selectedoption}}'
	}
});