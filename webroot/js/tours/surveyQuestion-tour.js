$(document).ready(function (){
  
var tour = new Tour({
steps: [

{
  element: "#surveyquestion", //survey questions,
  title: "Survey Questions?",
  content: "This is a list of your compliance survey questions. These questions will appear on each patient's account as an easy way for staff to track and record points for compliance. Default points have been set for each question but can be edited at any time.",
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
  element: "#surveyPoints",//-----add user,
  title: "Edit Survey Question Points",
  content: "Click here and enter the desired point value.",
  placement:"right",
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
 //   content: "If you have any other questions, contact us at help@buzzydoc.com.",
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