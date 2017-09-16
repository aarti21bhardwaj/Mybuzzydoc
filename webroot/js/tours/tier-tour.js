$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //tier programs,
  title: "What is a Tier Program?",
  content: "The tier program allows you define the unique rewards levels your patients will strive to obtain. Each tier will allow the patient to earn ‘cash back’ (based on percentages) on the amount they spend with your practice. This % cashback is given in the form of points. Gift coupons can also be added as a bonus for reaching new levels/tiers. The patient can redeem their earned points at any time for an e-gift card, in-office product or service.<br>On the tier program your patients are striving to obtain new tier levels within a one year period and can maintain that level for the next year.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addTier",//-----add vendor email settings,
  title: "Adding a Tier Program",
  content: "Adding your custom Tier Program is easy using the following steps.",
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
  element: "#tierName",
  title: "Step 1: Tier Names",
  content: "Add the names you want to identify each tier level. These names will show up on the patient’s account when they reach a new tier. Example: Bronze, Silver and Gold.",
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
  element: "#lowerBound",
  title: "Step 2: Minimum Spend Level",
  content: "Set the minimum amount a patient needs to spend to reach your tier level.",
  // content: "The next step is to choose the type of Milestone Program you need. It can be unlimited or limited as per your need. How and when your patients will achieve a milestone is based on the type of program you choose.  <br> In an <b>unlimited program</b> your can set the number of perfect score surveys the patient must achieve to earn the milestone. The milestone will be achieved every time the patient completes the set number of perfect scores on the compliance survey. You can set the reward the patient will earn. <br> In a <b>limited program</b> you can divide the patient visits into different phases and each phase can have its own perfect survey milestone. When the first perfect score survey for Phase 1 is hit, the milestone is achieved. Once a milestone has been achieved for a phase the patient proceeds to the next phase. By default we have set the type to unlimited.",
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
  element: "#upperBound",
  title: "Step 3: Maximum Spend Level",
  content: "Set the maximum amount that can be spent in a tier.",
  // content: "The next step is to add the phase name.The name you add here will be seen on the patient’s profile after they have achieved a milestone.",
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
  element: "#multiplier",
  title: "Step 4: Percent Multiplier",
  content: "Set the % multiplier in which your patient will earn points back based on their spend. Example: 2% multiplier set. Patient spends $500 and earns 500 points ($10).",
  // content: "The next step is to add the phase name.The name you add here will be seen on the patient’s profile after they have achieved a milestone.",
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
  title: "Step 5: Set Bonus Gift Coupons (Optional)",
  content: "You have the option to add a gift coupon bonus when patients reach a new tier level. Example: Gold Tier obtained = Patient now earns 2.5% back and receives a $25 Gift Coupon bonus",
  // content: "The next step is to add the phase name.The name you add here will be seen on the patient’s profile after they have achieved a milestone.",
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
  element: "#saveTier",
  title: "Step 6: Save",
  content: "After you have completed steps 1-5, click “Submit” to confirm your tier and repeat steps to add more levels depending on your need.",
  // content: 'After you have completed steps 1-5, Click on “Save”  to save your milestone program.',
  placement:"top",
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
  element: "#", //tier programs,
  title: "How to Award Points with a Tier Program? ",
  content: "On a patient’s account, click the “Amount Spent” tab. Enter the dollar amount spent by the patient and click “Award”. The points will automatically be recorded based on your percent multiplier settings.",
  placement:"top-left",  
  backdrop: true,
  orphan: true,       //------for steps not attached to any element
  onNext: function (tour){
      tour.end();
  }
},

{
  element: "#", //tier programs,
  title: "How to Award Points with a Tier Program? ",
  content: "On a patient’s account, click the “Amount Spent” tab. Enter the dollar amount spent by the patient and click “Award”. The points will automatically be recorded based on your percent multiplier settings.",
  placement:"top-left",  
  backdrop: true,
  orphan: true,       //------for steps not attached to any element
  onNext: function (tour){
      tour.end();
  }
}

 // {
 //   element: "#",
 //   title: "",
 //   content: "For More Information, Click Here. (Provide link to the uservoice article). If you have any other questions, contact us at help@buzzydoc.com.",
 //   placement:"top", 
 //   backdrop: true,
 //   orphan: true
 //  }

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