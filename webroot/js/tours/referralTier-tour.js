$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", 
  title: "What is a Referral Tier Program?",
  content: "The Referral Tier Program allows you to define unique rewards levels your patients will achieve for bringing in referrals. Each referral tier will allow the patient to earn rewards based on the number of referrals they bring in within a year. Rewards will be awarded when the referred patient starts treatment with your practice. Gift coupons can also be added as a bonus for reaching new tier levels. The patient can redeem their earned points at any time for an e-gift card, in-office product or service.<br>On the Referrals Tier Program your patients are striving to obtain new referral tier levels within a one year period and can maintain that level for the next year.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addReferralTier",
  title: "Adding a Referral Tier Program",
  content: "Add your custom Referral Tier Program with the following steps:",
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
  element: "#referralTierName",
  title: "Step 1: Referral Tier Names",
  content: "Add the names you want to identify each tier level with. These names will show up on the patient’s account when they reach a new referral tier. Example: Bronze, Silver and Gold.",
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
  element: "#requiredReferral",
  title: "Step 2: Referrals Required",
  content: "Set the minimum number of referrals a patient needs to bring in to reach your referral tier level.",
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
  element: "#points",
  title: "Step 3: Points",
  content: "Set the number of points your patient will earn when they reach a referral tier level.",
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
  element: "#giftCoupon",
  title: "Step 4: Set Bonus Gift Coupons (Optional)",
  content: "You have the option to add a gift coupon bonus when patients reach a new referral tier level. Example: Gold Tier obtained = Patient earns 100 points and receives a $25 Gift Coupon bonus.",
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
  element: "#saveReferraltier",
  title: "Step 5: Save",
  content: "After you have completed steps 1-4, click “Save” to confirm your new tier and repeat to add more levels depending on your need.",
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