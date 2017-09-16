$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", 
  title: "What is the Florist Program?",
  content: "The Florist Program allows you to send flowers to your patients for different occasions (birthdays, anniversaries, office inconvenience, etc). You can choose a flower option from the provided list and set it as your default setting.  All orders will be approved and paid for by the Staff Admin before they can be processed further.<br>*You can override the default settings and choose a different flower option from the patient dashboard.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addflowerSetting",
  title: "Adding the Florist Program",
  content: "Add your Florist Program using the following steps:",
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
  element: "#flower",
  title: "Step 1: Flower",
  content: "Choose the default flower arrangement that will show up on a patient’s account when sending flowers.<br>*You can always override the default flower setting for different patients from the patient’s account when sending flowers.",
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
  element: "#message",
  title: "Step 2: Message",
  content: "Choose the default message that will be delivered with the arrangement.<br> *You can always override the default message for patients from their account when sending flowers.",
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
  element: "#address",
  title: "Step 3: Billing address",
  content: "Set the default billing address (your practice’s address) for all flower orders.",
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
  element: "#save",
  title: "Step 4: Save",
  content: "Save your settings by clicking the “Save Changes” button.",
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