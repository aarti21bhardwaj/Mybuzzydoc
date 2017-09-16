$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", 
  title: "What are Instant Rewards?",
  content: "The Instant Rewards program allows you to offer your patients exclusive rewards coupons if they earn set number of points (points earned threshold) or spend a set amount (amount spent threshold) during one visit to your practice. The patient will have a set time to hit the threshold (points earned or amount spent). After the threshold is hit the instant rewards will be available for a set time period during which the patient has to decide if they want to make use of their exclusive offer.<br> *Here you can add the settings as well as the instant rewards coupons that will be available to the patient for redemption.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addInstantGiftCoupon",
  title: "Adding Instant Rewards Coupons Settings",
  content: "Add/Edit your custom settings using the following steps:",
  placement:"right",
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  }
},

{
  element: "#amtSpent",
  title: "Step 1: Amount Spent",
  content: "Set the minimum amount a patient needs to spend in one visit to be eligible for an instant reward coupon.",
  placement:"right", 
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  }
},
{
  element: "#pointsEarned",
  title: "Step 2: Points Earned",
  content: "Set the minimum points a patient needs to earn in one visit to be eligible for instant rewards.",
  placement:"right", 
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  }
},
{
  element: "#timePeriodForAchievingThreshold",
  title: "Step 3: Time period for Achieving threshold",
  content: "Set the time period (in hours) the patient has to achieve the amount spent or points earned threshold.",
  placement:"right", 
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  }
},
{
  element: "#instantRewardsAvailableForRedemption",
  title: "Step 4: Instant Rewards Expiration",
  content: "Time period (in hours) in which the instant reward coupon is available to the patient for redemption.",
  placement:"right", 
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  }
},

{
  element: "#submit",
  title: "Step 5: Save",
  content: "Save your Instant Rewards Coupons settings by clicking on the Submit button.",
  placement:"top",
  backdrop: true,
  backdropContainer: 'body',
  onShown: function (tour){
      $('body').addClass('tour-open')
  },
  onHidden: function (tour){
      $('body').removeClass('tour-close')
  },
  onNext: function (tour){
      tour.end();
  }
},



]});

$('<style>.tour-step-backdrop { background-color: white; }</style>').appendTo('body'); 
// Initialize the tour
// tour.init();

$('.startTour').click(function(){
tour.restart();

// Start the tour
 //tour.start();
})

});