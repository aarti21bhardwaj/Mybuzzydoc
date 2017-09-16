$(document).ready(function (){
  
var tour = new Tour({
steps: [
{
  element: "#", //gift coupon,
  title: "What are Gift Coupons?",
  content: "Gift coupons can be used for a ‘Dollar Amount Off’, a ‘% Discount’ or a ‘Free Service’ your patients can redeem their points for. You will have the ability set an expiration date for your coupons.",
  placement:"top-left",  
  backdrop: true,
  orphan: true//------for steps not attached to any element
},
{
  element: "#addGiftCouponTitle",//-----add gift coupon,
  title: "How to Add a Gift Coupon?",
  content: "Adding a custom gift coupon is easy using the following steps.",
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
  element: "#giftCouponDesc",
  title: "Step 1: Description",
  content: "Add the description for your Gift Coupon (i.e Is the Gift Coupon for a ‘Dollar Amount Off”, a “% Discount” or a “Free Service”?).",
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
  element: "#giftCouponPoints",
  title: "Step 2: Assign Point Value",
  content: "Add the point value a patient will need to redeem for the particular gift coupon.",
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
  element: "#expiry-duration",
  title: "Step 3: Coupon Expiration",
  content: "Set the time (in days) until the issued gift coupon becomes invalid or expires.",
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
  element: "#saveGiftCoupon",
  title: "Step 4: Save",
  content: "Save your new gift coupon by clicking ‘Submit’.", // link of helpdesk article
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
  title: "How to Issue and Redeem a Gift Coupon?",
  content: "To <b>issue a gift coupon</b>, go to a patient’s account and click on the “Redeem” tab, then the “Gift Coupon” tab. Select the gift coupon the patient wants by clicking the “Redeem” button.<br><br><b>*</b>A gift coupon can only be redeemed if the patient has a sufficient point balance. After a gift coupon has been successfully issued it will be seen on the patient’s account and can be used at any time by clicking on it. ", // link of helpdesk article
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
tour.restart();

// Start the tour
 //tour.start();
})

});