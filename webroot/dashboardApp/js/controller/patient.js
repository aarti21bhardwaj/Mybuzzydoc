//Injecting the Patient factory made in app.js into this controller
dashboardApp.controller('PatientController', function($window, $scope, $http, Patient, Vendor, isAuthorized, GiftCoupons, $timeout, Plan, $location){
	var patientCtrl = this;
	$scope.pat = Patient.data;
	$scope.vendor = Vendor.data;
	$scope.staffId = Vendor.loggedInUserId;
	$scope.activeGiftCoupons = false;
	$scope.promotions =  'abc';
	$scope.vendorPromotions = Vendor.data.vendor_promotions;
	$scope.selectedPromotions = [];
	$scope.promotionsObj = [];
	$scope.phistory = Patient.activityHistory;
	$scope.plan = Plan.activefeatures;
	$scope.patientsLastMilestone  = Patient.lastMilestoneAchieved;
	$scope.activeGiftCoupons = Patient.giftCoupons;
	$scope.referralsCount = Patient.referralsCount;
	$scope.user = {};
	$scope.awardTabs = ['tab1', 'tab2', 'tab3', 'tab4'];
	$scope.sortablePromos = false;
	$scope.sortablePromosInit = false;
	$scope.promoMultiplier = {};
	$scope.note = {};
	$scope.description = {};
	$scope.firstTab = false;
	$scope.allowRedemptions = false;

	// $scope.showSortPromo = false;
	//List of Award Tabs
	$scope.tabList = {
		
		tab1:{
				id:1,
				name: "Practice Promotions",
				iconClass:"fa-th-list",
				click: function(){
					$scope.tabSwitch(this.id);
					// $scope.sortableVendorPromotions();

				},
				viewCondition : $scope.auth('promotions')
		},
		tab2:{

				id:2,
				name: "Amount Spent",
				iconClass:"fa-usd",
				click: function(){
					$scope.tabSwitch(this.id);
				},
				viewCondition : $scope.auth('tier')
		},
		tab3:{

				id:3,
				name: "Manual Points",
				iconClass:"fa-money",
				click: function(){
					$scope.tabSwitch(this.id);
				},
				viewCondition : $scope.auth('manualpoints')
		},
		tab4:{

				id:4,
				name: "Compliance Survey",
				iconClass:"fa-pencil-square-o",
				click: function(){
					$scope.tabSwitch(this.id);
					$scope.surveyInit();
				},
				viewCondition : $scope.auth('compliancesurvey')
		}

  	};

	//sort order for tabs
	getSortOrder();
	getPromSortOrder();

	function getSortOrder(){

		$scope.sortOrder = $.cookie("tabs_sort_order");// read from cookie
		if(typeof $scope.sortOrder != "undefined"){
			//if cookie is found then use order
			$scope.sortOrder = JSON.parse($scope.sortOrder);
			//if no. of tabs have changed
			if($scope.awardTabs.length != $scope.sortOrder.length){

				$scope.sortOrder = $scope.awardTabs;
			}

		}else{
			//else use default order
			$scope.sortOrder = $scope.awardTabs;
		}

		for(x in $scope.sortOrder){
			if($scope.tabList[$scope.sortOrder[x]].viewCondition){
				$scope.firstTab = $scope.sortOrder[x];
				break;
			}
		}

	}

	function getPromSortOrder(){

		if(typeof $scope.vendor.vendor_promotions == "undefined" || $scope.vendor.vendor_promotions.length <= 0){
			
			return false;
		}

		$scope.getPromSortOrder = $.cookie("promotions_sort_order");// read from cookie
		if(typeof $scope.getPromSortOrder != "undefined"){
			
			//if cookie is found then use order
			$scope.getPromSortOrder = JSON.parse($scope.getPromSortOrder);
			//if order has duplicate entries then filter the order
			$scope.getPromSortOrder = $scope.getPromSortOrder.filter( function( item, index, inputArray) {
			           return inputArray.indexOf(item) == index;
			    });

			var sortIndex = -1;
			//object for mapping promos to sort order
			var oldPromos = {};
			//array of new promos which are not present in sort order
			var newPromos = [];
			var temp = [];

			//mapping promos with sort order if present else considered as new promos 
			$scope.vendor.vendor_promotions.map(function(item, index){
				
				sortIndex = $scope.getPromSortOrder.indexOf("prom"+item.id);
				if(sortIndex == -1){
					newPromos.push(item);
				}else{
					oldPromos["prom"+item.id] = angular.copy(item);  
				}
			});

			//sorting promotions into correct order
			for(x in $scope.getPromSortOrder){

				if(typeof oldPromos[$scope.getPromSortOrder[x]] != 'undefined'){
					temp.push(oldPromos[$scope.getPromSortOrder[x]]);
				}
			}

			//forming the array of vendor promotions in required order
			$scope.vendor.vendor_promotions = temp.concat(newPromos);
		}

	}
	
	sortableTabs();
	

	//Initiate award tabs as sortable tabs
	function sortableTabs(){
		$window.tabs = $( "#awardTabs" ).tabs();
		$window.tabs.find( ".ui-tabs-nav" ).sortable({
  			axis: "y",
  			stop: function() {
    			$window.tabs.tabs( "refresh" ); 
    			$scope.tabsInit = 1;
  			},
  			update: function(){

  				var order = $(".ui-tabs-nav").sortable("toArray");
  				order = JSON.stringify(order);
	        	$.cookie("tabs_sort_order",order, {expires: 365, path: '/'});
  				//resort vendor promotions
  				$scope.vendor.vendor_promotions.map(function(item, index){
				
					sortIndex = order.indexOf("prom"+item.id);
					if(sortIndex != -1 && sortIndex != index && sortIndex <= $scope.vendor.vendor_promotions.length -1){

						promObj = angular.copy($scope.vendor.vendor_promotions[sortIndex]);
						$scope.vendor.vendor_promotions[sortIndex] = angular.copy(item);
						$scope.vendor.vendor_promotions[index] = angular.copy(promObj);
					}
				});
  			},
		});
	}

	$scope.$watch(function(){
		return $scope.sortablePromos;
	}, function(newValue, oldValue) {

		clearPromotions();
	});

	$scope.$watch(function(){
		return Patient.allowRedemptions;
	}, function(newValue, oldValue) {
		$scope.allowRedemptions = angular.copy(Patient.allowRedemptions);
	});

	$scope.disableSortableVendorPromotions = function(){
		
		$scope.sortablePromos = false;
		var order = $("#vendorPromotions").sortable("toArray");
		order = order.filter( function( item, index, inputArray) {
           return inputArray.indexOf(item) == index;
	    });
		order = JSON.stringify(order);
		$.cookie("promotions_sort_order",order, {expires: 365, path: '/'});
		$('#vendorPromotions').sortable('disable');
		getPromSortOrder();

	}

	$scope.enableSortableVendorPromotions = function(){

		$scope.sortablePromos = true;
		$('#vendorPromotions').sortable('enable');

	}

	$scope.initSortableVendorPromotions = function(){

		$scope.sortablePromos = true;
		$('#vendorPromotions').sortable({
			axis: "y"
					// update: function(){


  	// 		},

		});

		$scope.sortablePromosInit = true;

		// $('#vendorPromotions').sortable('enable');

	}

	$scope.toggleRedemptions = function(){

		Patient.allowRedemptions = $scope.allowRedemptions;

		var url = $window.host+"api/VendorPatients/"+Patient.details.vendorPatient;

		var data = {

			redemptions: $scope.allowRedemptions
		}

		if($scope.allowRedemptions){

			var message = "Redemptions for this patient are now enabled.";
		}else{
			var message = "Redemptions for this patient are now disabled."
		}

		$http.put(url, data).then(function(response){
			
			swal("Success!", message, 'success');

		},function(response){
		
			swal("Error!", response.data.message, 'error');			
		
		});
	}

	function clearPromotions(){

		$scope.selectedpromo = {};	
		$scope.selectedPromotions = [];
		$scope.promotionsObj=[];
		$scope.promoMultiplier = {};
		for(x in $scope.vendor.vendor_promotions){
			$scope.promoMultiplier[$scope.vendor.vendor_promotions[x].id] = 1;
		}
	}
	
	// $scope.$watch(function(){
	// 	return $scope.frequency;
	// }, function(newValue, oldValue) {
	// 	if(typeof $scope.vendor.vendor_promotions != 'undefined' && typeof $scope.frequency != 'undefined'){
	// 		visiblePromoLength = 0;
	// 		for(x in $scope.frequency){
	// 			if($scope.frequency[x] == true){

	// 				visiblePromoLength++;

	// 			}

	// 		}

	// 		if(visiblePromoLength < $scope.vendor.vendor_promotions.length){
	// 			$scope.showSortPromo = false;
	// 		}else{
	// 			$scope.showSortPromo = true;
	// 		}
	// 	}
	// });

	//set patient details when the api returns response
	$scope.$watch(function(){
		return Patient.details;
	}, function(newValue, oldValue) {
		$scope.pdetails = Patient.details;
	});

	//set patient referral tiers when the api returns response
	$scope.$watch(function(){
		return Patient.details;
	}, function(newValue, oldValue) {
		$scope.referralTier = Patient.referralTier;
	});

	//set patients last milestone achieved
	$scope.$watch(function(){
		return Patient.lastMilestoneAchieved;
	}, function(newValue, oldValue) {
		$scope.patientsLastMilestone = Patient.lastMilestoneAchieved;
	});

	$scope.$watch(function(){
		return Patient.referralsCount;
	}, function(newValue, oldValue) {
		$scope.referralsCount = Patient.referralsCount;
	});

	// $scope.pdetails = Patient.details;
	
	$scope.promotionAwards = $scope.phistory.promotions;
	$scope.showPromo = false;
	$scope.awardPromoBtn = false;

	if($scope.vendorPromotions.length == 0){


		$scope.showPromo = false;
		$scope.promoErrorMessage = "No promotions are published";

	}

	$scope.$watchGroup([ function(){
	   return Patient.giftCoupons;
	}, function(){
	   return Patient.activityHistory;

	}], function(newValue, oldValue, scope){

		if(Patient.activityHistory !== false) {
				
				if(Patient.activityHistory.promotions.length == 0)
				{
					setNewFrequency();

				}else{

					setFrequency();
				}
		}

		$scope.activeGiftCoupons = Patient.giftCoupons;



	});

	// $scope.$watch(function(){
	// 	return Patient.activityHistory;
	// }, function(newValue, oldValue){
	// 		//Logic to display or hide the menu
			
	// 	});

	if($scope.phistory){
		console.log($scope.phistory);
	}
	
	var config = {
		headers:{"accept":"application/json"}
	};

	$scope.goToRefHistory = function(){

		$location.path('/activityHistory');
	}

	//TODO

	function setFrequency(){
		
		console.log("set");
		date = Date.parse(Date());
		$scope.promotionFrequency = {};
		$scope.previousPromos = {};
		$scope.frequency =  [];

			// console.log("Here");
			// console.log(Patient.activityHistory.promotions);

			for(key in Patient.activityHistory.promotions){

				$scope.previousPromos[Patient.activityHistory.promotions[key].vendor_promotion_id] = Patient.activityHistory.promotions[key].created;
			}

			for(key in $scope.vendorPromotions){

				frequency =  $scope.vendorPromotions[key].frequency;

				if (typeof $scope.previousPromos[$scope.vendorPromotions[key].id] == "undefined"){
					$scope.frequency[$scope.vendorPromotions[key].id] = true;
					$scope.showPromo = true;

				}else{

					created = Date.parse($scope.previousPromos[$scope.vendorPromotions[key].id]);
					difference = (date-created)/(1000*3600*24);
					if(created != "" && (difference >= frequency || frequency == 0)){

						$scope.frequency[$scope.vendorPromotions[key].id] = true;
						$scope.showPromo = true;

					}
				}
				
			}

			if($scope.showPromo == false){

				$scope.promoErrorMessage = "No promotions are available at the moment";

			}


		}

		function setNewFrequency(){
			console.log("set New");
			$scope.frequency =  [];
			for(key in $scope.vendorPromotions){

				$scope.frequency[$scope.vendorPromotions[key].id] = true;
				$scope.showPromo = true;

			}
		}

		$scope.postPatientMgmt = function(){

			if($scope.selectedPromotions.length != 0){
				console.log($scope.selectedPromotions);
				$scope.promotionsObj = [];

				for(sp in $scope.selectedPromotions){
					console.log('here2');
					for(vp in $scope.vendorPromotions){
						console.log('here3');
						if($scope.vendorPromotions[vp].id == $scope.selectedPromotions[sp]){
							console.log('selectedPromotions id is :'+$scope.selectedPromotions[sp]);
							console.log('vendorPromotion is: ');
							console.log($scope.vendorPromotions[vp]);
							$scope.description[$scope.selectedPromotions[sp]] = $scope.vendorPromotions[vp].promotion.description;
							if($scope.note[$scope.selectedPromotions[sp]]){
								$scope.description[$scope.selectedPromotions[sp]] = $scope.description[$scope.selectedPromotions[sp]] + ' ' + $scope.note[$scope.selectedPromotions[sp]];	
							}  
						}
					}
				}
				console.log('here');
				console.log($scope.description);
				$scope.selectedPromotions.map(function(item, index){

					$scope.promotionsObj.push({promotion_id : item, multiplier: $scope.promoMultiplier[item], description: $scope.description[item]});

				});				



				var postUrl = $window.host+"api/VendorPromotions/vendorPromotionPoints";

				var dataForPost = {
					"promotions" : $scope.promotionsObj
				};
				var configForPost = {
					"Content-Type" : "application/json",
					"Accept" : "application/json"
				};

				$scope.promoMessage = '';
				$http.post(postUrl, dataForPost, configForPost)
				.then(function(response){
					console.log(response.points);
					$scope.promoMessage ="Total points you are awarding: "+response.data.response.points;
					swal({
						title: "Are you sure you want to award the points?",
						text: $scope.promoMessage,
						type: "success",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Yes",
						closeOnConfirm: true
					}, function(isConfirm){
						  if(isConfirm) {
						    awardPromotionPoints();
						  }else{

						  	clearPromotions();		
							$scope.$apply();	
						  }
					});
				}, function(response){
					console.log('Failure');
				});
			}
			else{

				$scope.awardPromoBtn = false;
				swal('Select some promotions to award points.', "", 'error');
			}
		}

		function awardPromotionPoints(){

			$scope.awardPromoBtn = true;
			console.log($scope.promotions);
			var awardRequest = {}; 
			awardRequest.selectedPromotions = $scope.promotionsObj;
			awardRequest.redeemersId = $scope.pat.id;
			awardRequest.user_id = $scope.user.prom;
			// if(typeof $scope.pat.email !== 'undefined' && $scope.pat.email.length){

			// 	awardRequest.attribute_type =  "email";
			// 	awardRequest.attribute 		= $scope.pat.email;
			// }else if(typeof $scope.pat.phone != 'undefined' && $scope.pat.phone.length){
			// 	awardRequest.attribute_type =  "phone";
			// 	awardRequest.attribute 		= $scope.pat.phone;

			// }else if(typeof $scope.pat.email == 'undefined' && typeof $scope.pat.phone == 'undefined'){
			// 	alert("Patient Not Registered, Kindly Register Patient");
			// }
			if(awardRequest.redeemersId){
				$http.post($window.host + "api/Awards/promotions",  awardRequest, config).then(function(response){

					swal("Awarded!", "The points have been awarded.", "success");
					Patient.loadEverything();
					console.log(response);
					$scope.awardPromoBtn = false;
					clearPromotions();

				}, function(response){

					swal("Error!", response.data.message, "error");
					console.log(response);
					$scope.awardPromoBtn = false;
					clearPromotions();

				});
			}

		}

		$scope.pushToArray = function(x){
			var found = $scope.selectedPromotions.indexOf(x);
			if(found > -1 ){
				$scope.selectedPromotions.splice(found, 1);
			}else{

				$scope.selectedPromotions.push(x);
			}
			console.log($scope.selectedPromotions);
		};

		$scope.setMultiplier = function(promotion){

			console.log(promotion);
			swal({
				  title: "Set multiplier for "+promotion.promotion.name,
				  text: "Set the multiplier for this promotion. For example, if a promotion has 10 points and you set a multiplier of 2, then a total of 20 points will be awarded for this promotion.",
				  type: "input",
				  inputType:"number",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  animation: "slide-from-top",
				  inputPlaceholder: "2"
				},
				function(inputValue){

					if (inputValue === false){

				  		return false;
				    }	 
				  
					if (inputValue === "") {
						swal.showInputError("You need to enter a multiplier");
						return false;
					}

					if(inputValue < 1){

						swal.showInputError("Multiplier cannot be less than 1");
						return false;	
					
					}else{

						$scope.promoMultiplier[promotion.id] = inputValue * 1;
						$scope.$apply();
						swal.close();
					}


				});


		}

		$scope.addNote = function(promotion){
			var inputValueText = '';
			if($scope.note[promotion.id]){
				inputValueText = $scope.note[promotion.id];
			}

			swal({
				  title: "Add note for "+promotion.promotion.name,
				  text: "Add a note for this promotion.",
				  type: "input",
				  inputType:"text",
				  inputValue: inputValueText,
				  showCancelButton: true,
				  closeOnConfirm: false,
				  animation: "slide-from-top",
				  inputPlaceholder: "Add a note"
				},
				function(inputValue){
					console.log(inputValue);
					if (inputValue === false){
				  		return false;
				    }if (inputValue === "") {
						swal.showInputError("You need to enter a note first.");
						return false;
					}else{

						$scope.note[promotion.id] = inputValue;
						$scope.$apply();
						swal.close();
					}
					console.log($scope.note);
				});
		}

		$scope.tabSwitch = function(x){

			for(key in $scope.awardTabs)
				$scope[$scope.awardTabs[key]] = false;
			$scope['tab'+x] = true;
		}

		$scope.redeemGiftCoupon = function(giftCouponAwardId){
			console.log(giftCouponAwardId);
			swal({
				title: "Are you sure you want to redeem the gift coupon?",
				type: "success",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				closeOnConfirm: true
			}, function () {
				gcRedemption(giftCouponAwardId);
				

			});
		}

		$scope.referralHistory = function(){

			Patient.historyTab = 9;
			$location.path('/activityHistory');
		}

		function gcRedemption(giftCouponAwardId){
			var url = $window.host+'api/giftCoupons/redemption';
			data = {
				gift_coupon_award_id: giftCouponAwardId,
			};
			// $scope.activeGiftCoupons = [];
			$http.post(url, data).then(function(response){

				swal("Awarded Successfully" , " ","success");
				$scope.rewardamount = "";
				Patient.loadEverything();
				// $scope.activeGiftCoupons = Patient.giftCoupons;



			}, function(response){

				swal("Sorry, could not award the coupon at this moment." ,"" ,"error");
				$scope.rewardamount = "";
			});
			return;
		}

		$scope.showTierInfo = function(){

			var tierPerks = '';

			if(typeof $scope.pat.tier[$scope.pat.tier.length-1].tier_perks !== 'undefined' && $scope.pat.tier[$scope.pat.tier.length-1].tier_perks.length != 0)
			{
				for(key in $scope.pat.tier[$scope.pat.tier.length-1].tier_perks)
				{
					tierPerks = tierPerks + "\n" + $scope.pat.tier[$scope.pat.tier.length-1].tier_perks[key].perk;
				}
				tierPerks = "The Patient gets following perks for being in " +  $scope.pat.tier[$scope.pat.tier.length-1].name + "\n" + 
				tierPerks;
				swal("Tier Perks", tierPerks);
			}

		}

		$scope.showReferralTierPerks = function(){
			
			if($scope.referralTier){

				if($scope.referralTier['perks']){

					var perks = '';
					for(key in $scope.referralTier['perks'])
					{
						perks = perks + "\n" + $scope.referralTier['perks'][key];
					}
					perks = "The Patient gets following perks for being in " +  $scope.referralTier['name'] + "\n" + 
					perks;
					swal("Rereferral Tier Perks", perks);
				}
			}
		}

		$scope.surveyInit = function(){

			$timeout(function(){
				Vendor.surveySwiper = $window.swiperInit();
			}, 1000);
		}

		$scope.auth = function(feature){
			return isAuthorized.check(feature);
		}

	// patientCtrl.cards = function(x=null){
	// 	if(!x){
	// 		return false;
	// 	}
	// 	return x.reduce(function(all, card){
	// 		return all + card.card_number + 'hehe';
	// 	})
	// }
});
