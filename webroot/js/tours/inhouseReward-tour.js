$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //inhouse  rewards,
  title: "In-House Rewards",
  content: "In-House Rewards are product or service prize options that are available to your patients to redeem their points for.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addReward",//-----
  title: "Adding a Reward?",
  content: "Adding rewards is very easy using the following the steps.",
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
  element: "#name",
  title: "Step 1: Reward Name",
  content: "Add the name of your new reward that will be visible on the patient’s account.",
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
  element: "#rewardCategory",
  title: "Step 2: Reward Category",
  content: "Choose the category of your reward. Example: Product, service or coupon.",
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
  element: "#productType",
  title: "Step 3: Product Type",
  content: "Add the product type for your reward. “In-Office” products will show up under the ‘Products’ tab on the patient’s account and “e-Gift Cards” products will show under the ‘Express Gift Cards’ tab.",
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
  element: "#points",
  title: "Step 4: Points and Dollar Amounts",
  content: "Set the point value or amount for the in-house reward. If your product type is “In-office” then you will add points, if your product type is an “e-Gift Card” you will to enter the amount (in $).",
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
  element: "#image",
  title: "Step 5 Image",
  content: "Add the image you want displayed for your in-house reward.",
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
  element: "#status",
  title: "Step 6 Status",
  content: "Remember to activate your reward so it shows up on a patient’s account.",
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
  element: "#saveReward",
  title: "Step 7: Save",
  content: "Save your new reward by clicking on “Submit”.",
  // content: 'After you have completed steps 1-5, Click on “Save”  to save your milestone program.',
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