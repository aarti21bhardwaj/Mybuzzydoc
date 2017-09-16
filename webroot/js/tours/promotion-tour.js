$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //vendor promotions,
  title: "What are Promotions?",
  content: "Promotions are the ways a patient can earn points through your rewards program. Some default promotions have been prefilled in your dashboard for your convenience. You can edit the default promotions or add your own custom promotions. Promotions checked as active will be seen on the patient’s account.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addPromotion",//-----add promotion,
  title: "How to add a Promotion?",
  content: "Adding Promotions is easy using the following steps.",
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
  element: "#promotionName",
  title: "Step 1: Promotion Name",
  content: "Add the name of your promotion (Ways to earn), this is how it will show up on the patient’s account.",
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
  element: "#promotionDesc",
  title: "Step 2: Description",
  content: "Add a brief description of the promotion (Ways to earn).",
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
  element: "#promotionPoints",
  title: "Step 3: Points",
  content: "Set the point value patients will earn for each promotion (Ways to earn).",
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
  element: "#promotionFrequency",
  title: "Step 4: Frequency",
  content: "Assign a frequency to your promotion if desired. Frequency is the number of days after which the promotion will be available on a patient’s account again after recording it.<br>Example: A 'Dental Check Up' can only be awarded twice a year; the frequency should be to 180 days.",
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
  element: "#savePromotion",
  title: "Step 5: Save",
  content: "Click ‘Submit’ to store your promotion.", // link of helpdesk article
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
  title: "Awarding Promotions (Ways to earn)?",
  content: "Go to the desired patient’s account and check box next to the promotions you want to record points for and then click the “Award” button.", // link of helpdesk article
  // content: 'After you have completed steps 1-5, Click on “Save”  to save your milestone program.',
  placement:"top",
  backdrop: true,
  backdropContainer: 'body',
  orphan: true,
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
// tour.goTo(0);
  tour.restart();
// Start the tour
 //tour.start();
})

});