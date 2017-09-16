var milestone = angular.module('milestone', []);

milestone.controller('MilestoneController', function($scope, $http, $window, $filter, $element){

	$scope.vmId = $window.vendorMilestoneId;

	var config = {  
		headers:  {
			'accept': 'application/json',
		}
	};

	init();

	function init(){

		$scope.request = new Object;
		$scope.levelIndex = 0;
		$scope.request.end_duration = 1; 
		$scope.request.fixed_term = '';
		$scope.config = {};
		$scope.rewardsError = false;
		$scope.vendors = $window.vendors;
		$scope.addCoupon = $window.host+"gift-coupons/add"
		// $scope.reward_types = [{'id':1, 'name' : 'Points'}];
		
		if(!$scope.vmId){
			
			setBlankMilestone();
			$scope.readProgram = false;
			$scope.request.fixed_term = '0';
			console.log($scope.request);

		}else{

			getProgram();
			
		}
		
		

	}

	function getProgram(){

		$http.get($window.host + "api/VendorMilestones/view/" + $scope.vmId, config).then(function(response){

			$scope.request = response.data.vendorMilestone;
			if($scope.request.fixed_term == true)
			{
				$scope.request.fixed_term = '1';

			}else{

				$scope.request.fixed_term = '0';

			}
			removeCreatedModified();
			$scope.readProgram = true;
			$scope.getVendorRewards();
			if($scope.request.fixed_term == '0'){

				$scope.addNew();
			}
			// $scope.request.milestone_levels[levelIndex].milestone_level_rewards[0].reward_type_id = '1';
			console.log(response);

		},function(response){

			console.log("Error in getting VendorMilestones");
			console.log(response);
			setBlankMilestone();	

		});


	}

	function removeCreatedModified(){

		delete $scope.request.created;
		delete $scope.request.modified;
		$scope.request.milestone_levels.map(function(obj){
				delete obj.id;
				delete obj.created;
				delete obj.modified;
			obj.milestone_level_rewards.map(function(rewards){					
					delete rewards.id;
					delete rewards.created;
					delete rewards.modified;
			});
			obj.milestone_level_rules.map(function(rules){
					delete rules.id;
					delete rules.created;
					delete rules.modified;
			});
		})
	}

	$scope.getVendorRewards = function(){

		$http.get($window.host + "api/VendorMilestones/getRewardTypes/"+$scope.request.vendor_id, config).then(
			function(response){

				console.log(response);
				$scope.reward_types = response.data.rewardTypes;
				$scope.gift_coupons = response.data.giftCoupons;

			}, 
			function(response){

				console.log('There was some error in getting reward types.');
				console.log(response);

			}
		);

	}

	this.checkId = function(){

		if(typeof $scope.request.id != "undefined" && $scope.request.id != "" && $scope.request.id != 1){	

			swal({
				  title: "Are you sure?",
				  text: "Are you sure you want to edit this program as it will reset the progress of all your patients for this program?",
				  type: "error",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "Yes",
				  closeOnConfirm: false
				},
				function(confirm){

					if(confirm == true){

						postRequest();

					}else{

						return false;
					}
					
				});
		}else{

			postRequest();

		}

		
	}

	function postRequest(){

		$scope.request.end_duration = 1; //For limited program, end duration is 1.

		if($scope.request.fixed_term == 0 && $scope.request.milestone_levels.length > 1){
			$scope.request.milestone_levels.splice(1, $scope.request.milestone_levels.length - 1);
			$scope.request.milestone_levels[0].level_number = 1;
			$scope.request.end_duration = 0; //For unlimited program, end duration is 0.

		}

		$scope.request.milestone_levels.map(function(obj){
				
			obj.milestone_level_rewards.map(function(rewards){

				if(typeof rewards.reward_type_id == "undefined" || (typeof rewards.reward_id =="undefined" && typeof rewards.points == "undefined" ) )
				{	
					$scope.rewardsError = true;
				}					
			});	
		});


		if($scope.rewardsError == true){

			swal("Configure Rewards", "Please configure your rewards", "error");
			$scope.rewardsError = false;

		}else{

			
			
				$http.post($window.host + "api/VendorMilestones/postMilestones", $scope.request,config).then(function(response){

					console.log(response);
					$scope.vmId = response.data.response.id;
					getProgram();
					swal('Saved', "Milestone program has been saved", "success");


				},function(response){
					
					console.log(response);
					swal('Not Saved', response.data.message, "error");	
				});
			

			
		}
	}

	function setBlankMilestone(){

		$scope.request.milestone_levels = [];
		
		for(var i = 0; i<=1; i++){
			var index = $scope.request.milestone_levels.length;
			$scope.request.milestone_levels[index] = {};
			blankReward(index); 
		}
			

	}

	this.reward = function(index){

		$scope.levelIndex = index;
	}

	$scope.addNew = function() {

		var index = $scope.request.milestone_levels.length;
		$scope.request.milestone_levels[index] = {};
		blankReward(index);

	}

	function blankReward(milestoneIndex){

		$scope.request.milestone_levels[milestoneIndex].milestone_level_rewards = [];
		var rewardIndex = $scope.request.milestone_levels[milestoneIndex].milestone_level_rewards.length;
		$scope.request.milestone_levels[milestoneIndex].milestone_level_rewards[rewardIndex] = {};
			

	}
	this.addNewReward = function() {

		var rewardIndex = $scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards.length;
		$scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards[rewardIndex] = {};
			
	}

	this.remove = function(field,index){

		$scope.request[field].splice(index, 1);
	}

	this.removeReward = function(index){
		
		$scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards.splice(index, 1);
	}

	this.checkReward = function(index, rewardId){

		if(rewardId == 1){

			delete $scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards[index].reward_id;
		
		}else if(rewardId == 2){

			delete $scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards[index].points;

		}else if(rewardId == ""){

			delete $scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards[index].reward_id;
			delete $scope.request.milestone_levels[$scope.levelIndex].milestone_level_rewards[index].points;
		}
	}

	this.clearRewards = function(){

		$scope.request.milestone_levels.map(function(obj){
			obj.milestone_level_rewards.map(function(rewards, index){					
				if(rewards.reward_type_id == 2){
				 	
				 	delete rewards.reward_id; 
				}
			});
		});
	}	

});

milestone.run(function($rootScope) {
  $rootScope.typeOf = function(value) {
    return typeof value;
  };
})

milestone.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value);
      });
    }
  };
});