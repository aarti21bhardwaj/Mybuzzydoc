$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#addUsers",//-----add user,
  title: "Adding Staff Users",
  content: "Adding staff users is easy with the following steps.",
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
  element: "#role",
  title: "Step 1: Roles",
  content: "Select a role for your new staff member. Staff Admins have all privileges and the ability to add/edit program settings. Staff Managers have limited access and can only perform activities available on the dashboard.",
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
  element: "#userLocation",
  title: "Step 2: Practice Location",
  content: "Add the practice location where the user will be working from.",
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
  element: "#name",
  title: "Step 3: First Name",
  content: "Add first name.",
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
  element: "#lastname",
  title: "Step 4: Last Name",
  content: "Add last name.",
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
  element: "#email",
  title: "Step 5: Email",
  content: "Add the user’s email address.",
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
  element: "#phoneNo",
  title: "Step 6: Phone Number",
  content: "Add the user’s phone number.",
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
  element: "#userName",
  title: "Step 7: User name",
  content: "Set up a username your staff member will login to the portal with.",
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
  element: "#password",
  title: "Step 8: Password",
  content: "Set password. The minimum password length is 8 characters.",
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
  element: "#saveUser",
  title: "Step 9: Submit",
  content: "Save the new user by clicking the “Submit” button.",
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