$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //vendor referral settings,
  title: "Practice Referral Settings",
  content: "You can manage what types of referral levels your patients can earn points for",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addReferralSettings",//-----add referral settings,
  title: "Add Practice Referral Setting",
  content: "Adding referral is easy using the following steps.",
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
  element: "#levelName",
  title: "Step 1: Referral Level Name",
  content: "Add a name for your new referral level. This is what will show up when giving points for a referral.",
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
  element: "#referralPoints",
  title: "Step 2: Referrer Points",
  content: "Add the point value the referring patient will earn when the referred patient starts treatment with your practice.",
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
  element: "#refereePoints",
  title: "Step 3: Referree Points",
  content: "Add the point value the referred patient will earn when they start treatment with your practice.",
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
  element: "#refereeGiftCoupon",
  title: "Step 4: Referree Gift Coupon (Optional)",
  content: "You can add a bonus gift coupon for the referred patient here. This is optional.",
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
  element: "#saveReferral",
  title: "Step 5: Save",
  content: "Save your settings with the “Submit” button. Repeat steps 1-5 for additional referral levels.",
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
  element: "#",
  title: "Where will these settings show up?",
  content: 'Your settings will show up when adding a newly referred patient. Choose the appropriate referral level to automatically give points to the referee and the referrer. These settings can be seen on the activity history of the referring patient or by clicking on "Reports" => “Referral Leads".',
  // content: 'After you have completed steps 1-5, Click on “Save”  to save your milestone program.',
  placement:"top",
  backdrop: true,
  backdropContainer: 'body',
  orphan: true,
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

 // {
 //   element: "#",
 //   title: "",
 //   content: "For more information, CLICK HERE. (Provide link to the uservoice article). If you have any other questions, contact us at help@buzzydoc.com.",
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