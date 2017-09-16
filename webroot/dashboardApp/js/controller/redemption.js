dashboardApp.controller('RedemptionController', function($timeout,$filter,$window, $scope, $http, $interval, Plan, $location,isAuthorized, Patient, RewardProducts, Vendor, GiftCoupons){
	var redemptionCtrl = this;
	$scope.rewards = RewardProducts.data;
	$scope.pdata = Patient.data;
	$scope.phistory = Patient.activityHistory;
	$scope.pdetails = Patient.details;
	$scope.vendorData = Vendor.data;
	$scope.plan = Plan.activefeatures;
	$scope.vendorGiftCoupons = GiftCoupons.vendorCoupons;
	$scope.vendorInstGiftCoupons = GiftCoupons.vendorInstCoupons;
	$scope.showInstantRewardsTab = false;
	$scope.addGiftCoupon = $window.host+'/GiftCoupons/add';
	$scope.addInstGiftCoupon = $window.host+'GiftCoupons/add/instant/';
	$scope.hostAddress = $window.host;
	$scope.time = 0;
	$scope.timeLeftForExpiry = 0;
	
	$scope.$watch(function(){
	   return Patient.allowRedemptions;
	}, function(newValue, oldValue){
		//Logic to display or hide the menu
		if(Patient.details != false && newValue == false) {
			$location.path('/patient');
			swal("Error!", "Redemptions are disabled for this patient.", "error");
		}
	});

	initButtons();
	// loadVendorGiftCoupons();
	// loadVendorInstantCoupons();

	if(isAuthorized.check('giftCoupons')){

		$scope.vendorGiftCoupons = GiftCoupons.vendorCoupons;
		loadVendorGiftCoupons();
	}

	if(isAuthorized.check('instantRewards')){

		$scope.vendorInstGiftCoupons = GiftCoupons.vendorInstCoupons;
		loadVendorInstantCoupons();
	}

	function initButtons(){

		$scope.instantBtn = false;
		$scope.redeemBtn = false;
		$scope.instantCreditBtn = false;
		$scope.couponBtn = false;
		$scope.timeLeftForExpiry = 0;

		if(!$scope.time && Patient.instantRewardsStatus.isInstantRewardUnlocked == true && Patient.instantRewardsStatus.isExpired == false){
			$scope.time = Patient.instantRewardsStatus.expiry;
	    	var expiryDate = new Date($scope.time*1000);
	    	// console.log(expiryDate);
	      	var currentDate = new Date();
	      	// console.log(currentDate);
	      	// $scope.x = Math.abs(expiryDate.getTime() / 1000 - currentDate.getTime() / 1000);
	      	console.log(expiryDate.getTime());
	      	console.log(currentDate.getTime());
	      	$scope.timeLeftForExpiry = (expiryDate.getTime() - currentDate.getTime());
	      	console.log($scope.timeLeftForExpiry);
		    $interval(function() {
		      $scope.timeLeftForExpiry -= 1000;
		    }, 1000);
		}	

	}
	
	$scope.$watch(function(){
	   return Patient.instantRewardsStatus;
	}, function(newValue, oldValue){
		if(newValue && newValue.isInstantRewardUnlocked == true && isAuthorized.check('instantRewards')){
			$scope.showInstantRewardsTab = true;
		}
	});

	$scope.$watch(function(){
	   return Patient.details;
	}, function(newValue, oldValue){
		//Logic to display or hide the menu
		if(newValue == false) {
			$scope.showAmazonTango = false;
		} else {
			$scope.showAmazonTango = true;
		}
	});

	$scope.$watch(function(){
	   return Patient.redeemTab;
	}, function(newValue, oldValue){
		if(!Patient.isRedirect){

			$scope.tabSwitch(Patient.redeemTab);
		}else{
			Patient.isRedirect = false;
		}
	});

	$scope.tabSwitch = function(x){
		if(Patient.redeemTab != false && isAuthorized.check('instantRewards')){
			x = angular.copy(Patient.redeemTab);
			Patient.redeemTab = false;
		}

		if(typeof x == "undefined"  || !x || x == null || x == ""){
			return;
		}

		var tabs = ['tab1', 'tab2', 'tab3', 'tab4', 'tab5']
		for(key in tabs)
			$scope[tabs[key]] = false;
		$scope['tab'+x] = true;
		
	}

	if(!$scope.rewards && isAuthorized.check('productsServices')) {
		
		RewardProducts.loadData().then(function(response){
			//handle success
			RewardProducts.update(response.data.vendorlegacyReward);
			$scope.rewards = RewardProducts.data;
		}, function(){
			//handle failure
		});
	}


	function loadVendorGiftCoupons(){
		if(!$scope.vendorGiftCoupons){
			GiftCoupons.loadVendorCoupons().then(function(response){
				GiftCoupons.updateVendorCoupons(response.data.response.giftCoupons);
				$scope.vendorGiftCoupons = GiftCoupons.vendorCoupons;
			});
		}
	}

	function loadVendorInstantCoupons(){
		if(!$scope.vendorInstGiftCoupons){
			GiftCoupons.loadVendorInstantCoupons().then(function(response){
				GiftCoupons.updateVendorInstantCoupons(response.data.response.giftCoupons);
				$scope.vendorInstGiftCoupons = GiftCoupons.vendorInstCoupons;
			});
		}
	}

	$scope.instantRewards = [
								 {
								 	id:1,
									name:"Amazon Rewards",
									label:"amazon",
									image:$window.host+"img/amazon_gift_card_small_resized.jpg",
									description:"The Amazon Card lets you choose from millions of items storewide so you can get exactly what you want. It can all be spent on one item, or many, and the great thing is it never expires. All you have to do is load the redemption code into your Amazon account and start filling your cart!",
								 },
								 {
								 	id:2,
									name:"Tango Rewards",
									label:"tango",
									image:$window.host+"img/tangocard-small.png",
									description:"<img src= '"+$window.host+"img/tango_img_2.png'> <br> The Tango Card lets you to choose from tons of different retail and restaurant gift card options. It can all be spent on one gift card, or many, and the great thing is it never expires! To learn more about Tango, <a href='https://www.tangocard.com/the-tango-card/' target='_new'>click here!</a>",
								 },
							 ];
	$scope.whatisthis = function(title, message){
			swal({title:"More about "+title, text: message , html:true, type:"success"});		
	}

	this.redeemInstant = function(rewardId, service, productId){
		
		if(typeof rewardId == 'undefined' || !rewardId){
			rewardId = 0;
		}

		if(typeof service == 'undefined' || !service){
			service = null;
		}

		if(typeof productId == 'undefined' || !productId){
			productId = null;
		}

		$scope.instantBtn = true;
		$scope.instantCreditBtn = true;

		if(productId == 3 || productId == null)
			var url = $window.host+'api/legacyRedemptions/instantGiftCredit';
		else
			var url = $window.host+'api/legacyRedemptions/instantRedemption';

		var rewardService = "rewardtype"+rewardId;
		console.log(rewardService);
		data = {
					redeemer_peoplehub_identifier : $scope.pdata.id ,
            		redeemer_name : $scope.pdata.name,
            		legacy_reward_id: rewardId,
            		service: service,
            		amount: $scope.rewardamount 
				};
		
		$http.post(url, data).then(function(response){

			swal("Redeemed Successfully" , " ","success");
			Patient.loadEverything();
			$scope.rewardamount = "";
			$scope.instantBtn = false;
			$scope.instantCreditBtn = false;


		}, function(response){

			swal("Redemption Failed" ,"" ,"error");
			console.log('Error in redeemInstant with response from server - '+response);
			$scope.rewardamount = "";
			$scope.instantBtn = false;
			$scope.instantCreditBtn = false;

		});

		
		
	}

	this.walletCredits = function(service){

		var walletCredits = $scope.pdetails.totalWalletCredits;

		if(walletCredits != 0 && typeof walletCredits != "undefined"){

			swal({
					title: "Are you sure?",
					text: "Are you sure you want to redeem "+ walletCredits+" points ("+$filter('currency')(walletCredits/50, '$', 2)+") as a "+ service +" gift card?" ,
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes",
					closeOnConfirm: true

				},function(){

					var data = {

						redeemer_peoplehub_identifier : $scope.pdata.id ,
	            		redeemer_name : $scope.pdata.name,
	            		service: service,
	            		points : walletCredits 
					};

					redeemLegacyReward(data); 


				});

		}else{

			swal("No Wallet Credits", "Patient doesn't have any wallet credits to redeem", "error");

		}
	}	

	function redeemLegacyReward(data){

		$scope.redeemBtn = true;

		$http.post($window.host+'api/legacyRedemptions/redeemLegacy', data).then(function(response){

			swal("Redeemed Successfully" , response.data.legacyRedemption.points + " points were successfully redeemed" ,"success");
			Patient.loadEverything();
			console.log(response);
			$scope.redeemBtn = false;

		}, function(response){

			swal("Error in redemption" ,response.data.message ,"error");
			$scope.redeemBtn = false;
			console.log('Error in redeeming credits with response from server - ');
			console.log(response);
		});
		

	}

	this.inhouse = function(reward){ 
		
		var rewardType = isAuthorized.check('creditType');
		if(rewardType == 'store_credit'){

			var credit = $scope.pdetails.totalStoreCredits;

		}else{

			var credit = $scope.pdetails.totalWalletCredits;
		}

		if(credit != 0 && typeof credit != "undefined"){

			swal({
					title: "Are you sure?",
					text: "Are you sure you want to redeem "+ reward.legacy_reward.name +" for "+ reward.legacy_reward.points+ " points",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes",
					closeOnConfirm: true
				},function(){

					var data = {

						redeemer_peoplehub_identifier : $scope.pdata.id ,
	            		redeemer_name : $scope.pdata.name,
	            		reward_type : rewardType,
	            		legacy_reward_id : reward.legacy_reward_id
					};

					redeemLegacyReward(data); 	
				});

		}else{ 

			swal("Not Enough Credits", "Patient doesn't have enough credits to redeem this product", "error");

		}

	}

	this.enterGiftCredit= function(service){


		swal({
		  title: "Instant Gift Credit",
		  text: "Enter amount",
		  type: "input",
		  inputType:"number",
		  showCancelButton: true,
		  closeOnConfirm: false,
		  animation: "slide-from-top",
		  inputPlaceholder: "10"
		},
		function(inputValue){
		  console.log(inputValue);
		  if (inputValue === false){

		  	return false;
		  } 
		  
		  if (inputValue === "") {
		    swal.showInputError("You need to enter a amount");
		    return false;
		  }

		  if(inputValue < 1){
		  	
		  	swal.showInputError("Amount cannot be less than 1");
		  	return false;	
		  
		  }else{

			  $scope.rewardamount = inputValue;
			  redemptionCtrl.redeemInstant(null, service);
			  swal.close();
		  	
		  }

		  
	});


	}

	this.giftCoupon = function(giftCouponId){
		
		
		swal({
                title: "Are you sure you want to award the gift coupon?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function () {
                
                redemptionCtrl.awardGiftCoupon(giftCouponId);
            });
		}

	this.awardGiftCoupon = function(giftCouponId){
		
		$scope.couponBtn = true;
		var url = $window.host+'api/awards/giftCoupon';
		data = {
					gift_coupon_id: giftCouponId,
					redeemer_name : $scope.pdata.name,
					redeemer_peoplehub_identifier : $scope.pdata.id
				};
		
		$http.post(url, data).then(function(response){

			swal("Awarded Successfully" , " ","success");
			$scope.rewardamount = "";
			Patient.loadEverything();
			$scope.couponBtn = false;


		}, function(response){

			swal("Error!" ,response.data.message ,"error");
			$scope.rewardamount = "";
			$scope.couponBtn = false;
		});
	}

	this.showData = function(){
		console.log(RewardProducts.data);
	}

	this.auth = function(feature){
		return isAuthorized.check(feature);
	}

	this.refreshGiftCoupons = function(){
		$scope.vendorGiftCoupons = false;
		loadVendorGiftCoupons();
	}

	this.refreshInstGiftCoupons = function(){
		$scope.vendorInstGiftCoupons = false;
		loadVendorInstantCoupons();
	}
});