//Creating the Patient Factory for access to other controllers
dashboardApp.factory('Tour', ['isAuthorized', '$window',function tourFactory(isAuthorized, $window){
	// Returning an object
		var obj ={};

		obj.steps = [];

	    obj.allSteps = {
	    
	    	'promotions' : [{
							  element: "#linktab1", //award promotion points,
							  title: "Awarding Promotion Points ",
							  content: "Check the promotion you want to issue points for and click “Award”. You also have the option to set a multiplier for the selected promotion. <br><b>*Promotion Multiplier</b>: If a promotion is valued at 10 points and you set the multiplier at 2, a total of 20 points will be awarded to the patient for the promotion",
							  placement:"right",  
							  backdrop: true,
							  backdropContainer: 'body',
							  onShown: function (tour){
							      $('body').addClass('tour-open')
							  },
							  // onNext: function(tour){
							  //     $("#linktab2").click();
							  // },
							  onHidden: function (tour){
							      $('body').removeClass('tour-close')
							  }


							  // orphan: true//------for steps not attached to any element
							}],
			'tier' : [{
								  element: "#linktab2",//-----award tiers,
								  title: "Awarding Amount Spent Points",
								  content: "Your tier program settings will be used in this section. Enter the amount spent by the patient during their visit and click “Award”. Points will automatically be issued according to the patient’s current tier level (% back multiplier) which can be seen on the left side on your screen.",
								  placement:"right",
								  backdrop: true,
								  backdropContainer: 'body',
								  onShown: function (tour){
								      $('body').addClass('tour-open')
								  },
								  // onPrev: function(tour){
								  //     $("#linktab1").click();
								  // },
								  //  onNext: function(tour){
								  //     $("#linktab3").click();
								  // },
								  onHidden: function (tour){
								      $('body').removeClass('tour-close')
								  }

							 }],
			'manualpoints' : [{
								  element: "#linktab3", //------manual awards
								  title: "Awarding Manual Points",
								  content: 'Enter the point value along with a descriptive note and click “Award".',
								  placement:"right", 
								  backdrop: true,
								  backdropContainer: 'body',
								  onShown: function (tour){
								      $('body').addClass('tour-open')
								  },
								  // onPrev: function(tour){
								  //     $("#linktab2").click();
								  // },
								  // onNext: function(tour){
								  //     $("#linktab4").click();
								  // },
								  onHidden: function (tour){
								      $('body').removeClass('tour-close')
								  }
							 }],	

			'compliancesurvey' : [
									{
									  element: "#linktab4", //-----compliance survey
									  title: "Taking a Compliance Survey",
									  content: "Answer the questions from the Compliance Survey each time the patient has an appointment.",
									  // content: "The next step is to choose the type of Milestone Program you need. It can be unlimited or limited as per your need. How and when your patients will achieve a milestone is based on the type of program you choose.  <br> In an <b>unlimited program</b> your can set the number of perfect score surveys the patient must achieve to earn the milestone. The milestone will be achieved every time the patient completes the set number of perfect scores on the compliance survey. You can set the reward the patient will earn. <br> In a <b>limited program</b> you can divide the patient visits into different phases and each phase can have its own perfect survey milestone. When the first perfect score survey for Phase 1 is hit, the milestone is achieved. Once a milestone has been achieved for a phase the patient proceeds to the next phase. By default we have set the type to unlimited.",
									  placement:"right", 
									  backdrop: true,
									  backdropContainer: 'body',
									  onShown: function (tour){
									      $('body').addClass('tour-open')
									  },
									   onNext: function(tour){
								    	  $("#linktab4").click();
								  		},
									  // onPrev: function(tour){
									  //     $("#linktab3").click();
									  // },
									  onHidden: function (tour){
									      $('body').removeClass('tour-close')
									  }
									},
									{
									  element: "#checkedSurvey",
									  title: "What is the “Count this question for Perfect Survey” checkbox?",
									  content: "If this box is checked the question will be counted as part of the perfect patient survey and will be counted towards the total points given. If you uncheck it, the question will not be considered part of the perfect patient survey or go towards the total points to be awarded.",
									  // content: "The next step is to add the phase name.The name you add here will be seen on the patient’s profile after they have achieved a milestone.",
									  placement:"top", 
									  backdrop: true,
									  backdropContainer: 'body',
									  onShown: function (tour){
									      $('body').addClass('tour-open')
									  },
									  onHidden: function (tour){
									      $('body').removeClass('tour-close')
									  }
									}
								 ],

				 'creditType' : [{
								 	  element: "#linkamazonGiftCard", //award promotion points,
									  title: "Amazon/Tango e-Gift Cards ",
									  content: "Your patients have the option to redeem their earned points for an instant Amazon or Tango e-gift card. The gift card redemption codes will be sent to their email address on file and can spend on whatever they want. Staff also have the option to redeem points for patients by clicking the “Redeem” tab on their account and selecting the desired prize.",
									  placement:"right",  
									  backdrop: true,
									  backdropContainer: 'body',
									   onShown: function (tour){
									      $('body').addClass('tour-open')
									  },
									  // onNext: function(tour){
									  //     $("#linkexpressGiftCards").click();
									  // },
									  onHidden: function (tour){
									      $('body').removeClass('tour-close')
									  }
				 				}],

			'instantGiftCredit' : [{

								      element: "#linkexpressGiftCards",//-----award tiers,
									  title: "Express Gift Cards",
									  content: "Express Gift Cards are a new addition to the BuzzyDoc program. You are able to gift instant Amazon/Tango e-cards to your patients without using their points. Enter the amount you wish to gift to your patient and click on “OK”. They will receive the e-gift card instantly to the email address on file.<br><b>*</b>Be sure you have setup a credit card on your account before giving Express Gifts.",
									  placement:"right",
									  backdrop: true,
									  backdropContainer: 'body',
									  onShown: function (tour){
									      $('body').addClass('tour-open')
									  },
									  // onPrev: function(tour){
									  //     $("#linkamazonGiftCard").click();
									  // },
									  // onNext: function(tour){
									  //     $("#linkgiftCoupon").click();		    
									  // },
									  onHidden: function (tour){
									      $('body').removeClass('tour-close')
									  },
									  // onRedirectError: function(tour){
									  //   console.log(tour.getCurrentStep());
									  // }
			             		  }],
			'giftCoupons' :       [{
									  element: "#linkgiftCoupon", //------manual awards
									  title: "Gift Coupons ",
									  content: 'Gift coupons can be used as a ‘Dollar Amount Off Certificate’, a ‘% Discount’ or a ‘Free Service’ your patients can redeem their points for. To issue a gift coupon for points click on the “Redeem” button. The issued gift coupon will be seen on the patient’s account.',
									  placement:"right", 
									  backdrop: true,
									  backdropContainer: 'body',
									  onShown: function (tour){
									      $('body').addClass('tour-open')
									  },
									  //  onPrev: function(tour){
									  //     $("#linkamazonGiftCard").click();
									  // },
									  onHidden: function (tour){
									      $('body').removeClass('tour-close')
									  }

							     }]
	    };
	    

	    obj.init = function(){

	    	for(x in obj.allSteps){

	    		console.log(x);
	    		var featureEnbaled = isAuthorized.check(x);
	    		console.log(featureEnbaled);
	    		if(featureEnbaled){
	    			
	    			for(y in obj.allSteps[x]){
	    				obj.steps.push(obj.allSteps[x][y]);
	    			}
	    		}
	    	}
			// console.log(obj.steps);
	    	$window.steps = obj.steps;

	    }
		return obj;
}]);