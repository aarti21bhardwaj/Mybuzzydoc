$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#",
  title: "Email Settings",
  content: "A list of default emails have been setup for your convenience. You can customize your own email templates and settings for any event from the provided list by clicking on the “Edit” button.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addEmail",
  title: "Email Settings",
  content: "Editing your email settings is easy using the following steps.",
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
  element: "#eventName",
  title: "Step 1: Event Name",
  content: "Select the email template you want to configure your own settings for. You can select any event from the provided list.",
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
  element: "#from",
  title: "Step 2: From",
  content: "Add the from email address",
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
  element: "#recipients",
  title: "Step 3: Recipients",
  content: "Add the email address which will receive a copy of this message every time this event occurs.",
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
  element: "#subject",
  title: "Step 4: Subject",
  content: "Add the email subject.",
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
  element: "#body",
  title: "Step 5: Body",
  content: "Add the content for the body of your email.",
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
  title: "Step 6: Status",
  content: "Control whether your patients/staff should receive emails for different events.<br>*This setting will be applicable to all patients/staff. ",
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
  element: "#saveEmail",
  title: "Step 7: Save",
  content: "Save your email with the “Submit” button.",
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