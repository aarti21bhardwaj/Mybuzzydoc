$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //vendor milestones,
  title: "What is Milestones Program?",
  content: "The milestone program gives you an option to reward your patients with a bonus when they complete a perfect score on the compliance survey a specified number of times. You have the ability to set the number criteria and rewards bonus your patients will earn. Once a program has been added it will apply to all of your patients. <br> <b>*</b>A survey score is considered perfect if all the questions on the compliance survey get a positive response.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addMilestoneTitle",//-----add milestone program,
  title: "Add Milestone Program",
  content: "Adding your custom milestone program is easy using the following steps. ",
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
  element: "#programName",
  title: "Step 1: Program Name",
  content: "The first step in setting up your own Milestone Program is to create a name. Tip: You can use the name of your practice.",
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
  element: "#programTypeLabel",
  title: "Step 2: Program Type",
  content: "Choose the type of Milestone Program you want to use; limited or unlimited. How and when your patients will achieve a milestone is based on the type of program you choose.<br>Click Here to learn more about the limited and unlimited program types.(provide a link to the user voice article)",
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
  element: "#levelName",
  title: "Step 3: Phase Names",
  content: "Add the phase names that will be seen on the patient’s account after they have achieved a milestone.<br>Example: Phase I and Phase II or 5 Perfect Visits and 10 Perfect Visits",
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
},

{
  element: "#cumulativePerfectPatient",
  title: "Step 4: Perfect Patient Frequency(cumulative)",
  content: "Set the number of perfect score surveys a patient needs to achieve a to obtain a milestone bonus reward.<br>Example: 5 Perfect Visits = 250 Bonus Points",
  // content: "The next step is to add the perfect patient(cumulative). This is the number of perfect score surveys a patient needs to achieve a milestone.",
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
  element: "#configureRewardsHeading",//------configure rewards button,
  title: "Step 5: Set Rewards",
  content: "Set the reward value a patient will receive when they achieve a milestone. The reward value can be points, gift coupons or both.<br>Click on the blue “Configure Rewards” button to add rewards for different phases.",
  // content: "The next step is to add the reward value a patient will receive when they achieve a milestone. The reward value can be points, Gift Coupons or both.",
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
  element: "#saveMile",
  title: "Step 6: Save",
  content: "After you have completed steps 1-5, click “Save” to save your new milestone program.", // link of helpdesk art
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

// {
//   element: "#",
//   title: "Congratulations!",
//   content: 'Now you know how to configure your milestones program successfully. For more information <a href="http://help.buzzydoc.com/knowledgebase/articles/1132168-milestones-program" target="_blank">click here</a>',
//   placement:"top", 
//   backdrop: true,
//   orphan: true
// },

{
   element: "#",
   title: "How to Award Points with the Milestone Program ",
   content: "Go to a patient’s account and click on the “Compliance Survey” tab. Take the survey for the patient and click “Submit”. Points will automatically be recorded based on your milestone program settings.",
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
tour.restart();

// Start the tour
 //tour.start();
})

});