$(document).ready(function(){

	changePoints();
	var searchPatientAjaxReq = false;
	$(".status input[type='checkbox']").change(function() {
		$('#dist-status-label').remove();
		if($(".status input[type='checkbox']").is(':checked')){
			$statusHTML =  '<span class="label label-success" id="dist-status-label">Enabled</span>';
		}else{
			$statusHTML =  '<span class="label label-warning" id="dist-status-label">Disabled</span>';
		}

		$(this).after($statusHTML);
	});


	//Used for Legacy Redemption Status Change on the index page
	$('select[name=status]').change(function(){
		var element_id = $(this).attr('redemption_id');
		var value = $(this).val();
		updateRedemptionStatus(element_id,value);
	});

	//Called when "Apply" button is clicked from legacyRedemptions index page.
	$('#bulk_update_button').click(function()
	{
		var id = [];
		$("input[name=selected_redemptions]:checked").each(function(){
			id.push($(this).attr('redemption-id'));
		});

		var status = $('select[name=bulk_update_status]').val();

		if(id.length){
			console.log(id);
			data = {'legacy_redemption_status_id':status,
			'redemption_id':id };
			updateBulkRedemptionStatus(data);
		}
	});

	//Called when place amazon order button is clicked from legacyRedemptions index page.
	var id = {};
	$('#amazon_place_order').click(function()
	{	
		$("input[name=selected_redemptions]:checked").each(function(){
			id[$(this).attr('redemption-id')] = $(this).attr('amazon-id');
		});
		
		placeOrderOnAmazon(id);
	});

	$("input[name=selected_redemptions_select_all]").change(function()
	{  //"select all" change
    	$("input[name=selected_redemptions]").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

	//" selected_redemptions" change
	$('input[name=selected_redemptions]').change(function(){
	    //uncheck "select all", if one of the listed checkbox item is unchecked
	    if(false == $(this).prop("checked")){ //if this item is unchecked
	        $("input[name=selected_redemptions_select_all]").prop('checked', false); //change "select all" checked status to false
	    }
	    //check "select all" if all checkbox items are checked
	    if ($('input[name=selected_redemptions]:checked').length == $("input[name='selected_redemptions'][type='checkbox']").length ){
	    	$("input[name=selected_redemptions_select_all").prop('checked', true);
	    }
	});


	$('#search-patient-btn').on('click',function(){
		$('#search-user-response-accordion').hide();
		$('#collapseSearchResponse .panel-body .list-group').remove();
		$("#collapseSearchResponse .panel-body").empty();
		var val = $('#search-patient-input').val();
		var attr = $('input[name=attrType]:checked','#search-patient-attr').val();
		var validated = true;
		if(!val){
			validated = false;
			if(!$('.search-input').hasClass('has-error')){
				$('.search-input').addClass('has-error');
				$('.search-input').append(' <label class="control-label search-error-label" for="search-patient-input">Kindly enter a value to search.</label>');
			}
		}else{
			if($('.search-input').hasClass('has-error')){
				$('.search-input').removeClass('has-error');
				$('.search-error-label').remove();
			}
		}
		var querystring = '?value='+ val;
		if(attr){
			querystring = querystring + '&attributeType=' + attr + '&vendor_id=2';
		}
		if(!searchPatientAjaxReq && validated){
			searchPatientAjaxReq =  $.ajax({
				method: "GET",
				url: "http://peoplehub.twinspark.co/peoplehub/api/users/search"+querystring,
				headers:{"accept":"application/json"},
				success: function(r) {
					if(r.response.status){
						if(r.response.data.users.length){
							var $newUserContainer = $('<div class="list-group"></div>');
							$newUserContainer.append('<div class="row"><strong><div class="col-lg-4">Registered Users</div></strong></div>');
							$newUserContainer.append('<div class="row"><strong><div class="col-lg-4">Name</div><div class="col-lg-4">Email</div><div class="col-lg-4">Phone</div></strong></div>');
							for (x in r.response.data.users) {
								$listItem = $('<a href="#" class="list-group-item"></a>');
								$listItem.append('<strong><div class="list-group-item-heading row"><div class="col-lg-4">'+r.response.data.users[x].name+'</div><div class="col-lg-4">'+r.response.data.users[x].email+'</div><div class="col-lg-4">'+r.response.data.users[x].phone+'</div></div></strong>');
								var cards = '';
								if(r.response.data.users[x].hasOwnProperty("user_cards") && r.response.data.users[x].user_cards.length){
									for(y in r.response.data.users[x].user_cards){
										cards = cards +', '+ r.response.data.users[x].user_cards[y].card_number;
									}
									cards = cards.replace(/(^,)|(,$)/g, "");
									$listItem.append('<p class="list-group-item-heading">cards: '+cards+'</p>');
								}

								$newUserContainer.append($listItem);
							}
							$("#collapseSearchResponse .panel-body").append($newUserContainer);
						}
						if(r.response.data.unassociatedUsers.length){
							var $newUserContainer = $('<div class="list-group"></div>');
							$newUserContainer.append('<div class="row"><strong><div class="col-lg-4">UnRegistered Users</div></strong></div>');
							for (x in r.response.data.unassociatedUsers) {
								$listItem = $('<a href="#" class="list-group-item"></a>');
								$listItem.append('<strong><div class="list-group-item-heading row"><div class="col-lg-4">'+r.response.data.unassociatedUsers[x].attribute_type+' = '+r.response.data.unassociatedUsers[x].attribute+'</div></strong>');
								$newUserContainer.append($listItem);
							}
							$("#collapseSearchResponse .panel-body").append($newUserContainer);
						}
						if(!r.response.data.users.length && !r.response.data.unassociatedUsers.length){
							$("#collapseSearchResponse .panel-body").append('<div class="alert alert-info" role="alert">No record Found</div>');
						}
					}
					$('#search-user-response-accordion').fadeIn(500);
					searchPatientAjaxReq = false;
				},
				beforeSend: function() {

				},
				complete: function() {
					searchPatientAjaxReq = false;
				}
			});
		}
	});
	$('#search-patient-input').on('keyup',function(event){
		if($('#search-patient-input').val().length){
			$('.search-input').removeClass('has-error');
			$('.search-input .search-error-label').remove();
			if(event.keyCode==13){
				$( "#search-patient-btn" ).trigger( "click" );
			}
		}else{
			if(!$('.search-input').hasClass('has-error')){
				$('#search-user-response-accordion').hide();
				$('#collapseSearchResponse .panel-body .list-group').remove();
				$('.search-input').addClass('has-error');
				$('.search-input').append(' <label class="control-label search-error-label" for="search-patient-input">Kindly enter a value to search.</label>');
			}
		}

	});

	$('#saveUserPassword').on('click',function(event){
		if($(this).hasClass('disabled')){
			event.preventDefault();
		}
		var oldPwd = $('#old_pwd').val();
		var userId = $('input[name=userId]').val();
		var newPwd = $('#new_pwd').val();
		var cnfNewPwd = $('#cnf_new_pwd').val();
		if(oldPwd && newPwd && cnfNewPwd && (newPwd == cnfNewPwd)){
			$.ajax({
				url: host+"api/users/updatePassword/",
				headers:{"accept":"application/json"},
				dataType: 'json',
				data:{
					"user_id" : userId,
					"old_password" : oldPwd,
					"new_password" : newPwd,
				},
				type: "post",
				success:function(data){
					if($('#rsp_msg').hasClass('alert-danger')){
						$('#rsp_msg').removeClass('alert-danger');
					}
					if($('#rsp_msg').hasClass('alert-success')){
						$('#rsp_msg').removeClass('alert-success');
					}
					$('#rsp_msg').addClass('alert-success');
					$('#rsp_msg').append('<strong>Password changed successfully.</strong>');
					$('#rsp_msg').show();
					setTimeout(function(){
						$('#rsp_msg').fadeIn(500);
						$('#changePasswordModal').modal('hide');
							$('#rsp_msg').removeClass('alert-success');
						$('#rsp_msg').hide();
						$('#rsp_msg').html('');
					}, 2000);
				},
				error:function(data){
					var className = 'alert-danger';
					if($('#rsp_msg').hasClass('alert-success')){
						$('#rsp_msg').removeClass('alert-success');
					}
					$('#rsp_msg').addClass(className);
					$('#rsp_msg').append('<strong>' + data.responseJSON.message + '</strong>');
					setTimeout(function(){
						if($('#rsp_msg').hasClass(className)){
							$('#rsp_msg').removeClass(className);
						}
						$('#rsp_msg').hide();
						$('#rsp_msg').html('');

					}, 2000);
					$('#rsp_msg').fadeIn(500);

				},
				beforeSend: function() {

				}
			});

		}
		event.preventDefault();
	});

});

var host = $('#baseUrl').val();

function updateRedemptionStatus(id, status_id) {

	if(typeof id == 'undefined' || !id){
		id = null;		
	}

	if(typeof status_id == 'undefined' || !status_id){
		status_id = null;		
	}

	$.ajax({
		method: "PUT",
		url: host+"api/legacy-redemptions/"+id,
		headers:{"accept":"application/json"},
		contentType: "application/json",
		data:'{"legacy_redemption_status_id":'+status_id+'}',
	})
	.success(function(data){
		updateLegacyRedemptionStatus(data);
	});
}

function updateLegacyRedemptionStatus(res){
	if(res.response.data)
		console.log(res);
}

function bindVendorPromotionsCheckbox(){
	//come back here
		//data saved when checkbox is checked
	$("input[name=published]").change(function(event){
		var that = $(this);
		// var edit = $('#editUrl').val();
		var promotionId = that.attr('data-promotion-id');
		
		if($(this).is(':checked'))
		{
			that.parents("tr").find('a.edit').show();
			var vendorId = that.attr('data-vendor-id');
			var points = that.attr('data-points');
			if(typeof that.attr('data-frequency') != 'undefined'){
				var frequency = that.attr('data-frequency');
			}else{
				var frequency = 0;
			}
			$.ajax({
				method: "POST",
				url: host+"api/vendorPromotions/add",
				headers:{"accept":"application/json"},
				//dataType: "json",
				contentType:"application/json",
				data:'{"vendor_id" : '+vendorId+',"promotion_id" : '+promotionId+',"points":'+points+',"frequency":'+frequency+'}',
			})
			.success(function(res){
				that.attr('data-vendor-promotion-id',res.response.id);
				that.attr('data-vendor-id',res.response.vendor_id);
				that.attr('data-points',res.response.points);
				that.attr('data-frequency',res.response.frequency);
				$('#vendorPromotionId'+promotionId).attr('value',res.response.id);
				that.parents('tr').find('a.edit').attr('href',res.response.link);
			});
		}else{
			$(this).parents("tr").find('a.edit').hide();
			$('#vendorPromotionId'+promotionId).attr('value',0);
			var id = $(this).attr('data-vendor-promotion-id');
			$.ajax({
				method: "DELETE",
				url:host+"api/vendorPromotions/delete/"+id,
				headers:{"accept":"application/json"},
				//dataType: "json",
				contentType:"application/json",
			})
			.success(function(res){
				that.attr('data-vendor-promotion-id','');
			});
		}
	});
}

function updateBulkRedemptionStatus(data){
	jsonData = JSON.stringify(data);
	$.ajax({
		method: "PUT",
		url: host+"api/legacy-redemptions/bulk-update",
		headers:{"accept":"application/json"},
		contentType: "application/json",
		data:jsonData,
	})
	.success(function(msg) {
	     bulkUpdateLegacyRedemptionStatus(msg, data); // setting bulk update array to blank.
	     console.log('success');
	 });
}

function bulkUpdateLegacyRedemptionStatus(msg,data){
	console.log(data.redemption_id);
	console.log(data.legacy_redemption_status_id);
	for (var i in data.redemption_id) {
		$('select[redemption_id='+data.redemption_id[i]+']').val(data.legacy_redemption_status_id);
	}

	$("input:checkbox").removeAttr('checked');
}


function disAllowDotInIntegerInput(event){
	var charCode = event.charCode;
	if(charCode==46){
		event.preventDefault();
	}

	/*function get_templateData(){
     	jQuery.ajax({
        type: "POST",
        url: host+'referrals/ajax_template_data/',
        //data: dataString,
            beforeSend: function(){
            },
            success: function (result) {
                jQuery('.show-data').html(result);
            }
        });
    }*/


}

/* this function gets the template data for a vendor */
function get_templateData(data){
	var get_id = data.value;
	jQuery.ajax({
		type: "GET",
		url: host+"api/referrals/"+get_id,
		headers:{"accept":"application/json"},
            //data: {get_template_id:get_id},
            success: function (result) {
            	$('#subject').val(result.referral_template_data.subject);
            	$('#description').val(result.referral_template_data.description);
            }
        });
}


/* this function gets the template list for the vendors */
function get_templates(data){
	jQuery.ajax({
		type: "GET",
		url: host+"api/referralTemplates/?vendor_id="+data.value,
		headers:{"accept":"application/json"},
		success: function (result) {
			updateTemplateOptions(result);
            	//console.log(result.referralTemplates.id);
            }
        });
}

function updateTemplateOptions(result){
	console.log(result.referralTemplates);
	values = result.referralTemplates;
	//var removeOptions = '<option>';
	$('#template_options').empty();
	$('#template_options').append('<option>Please Select</option>');
	$.each(values, function (i, values) {
		$('#template_options').append($('<option>', {
			value: values.id,
			text : values.subject
		}));

	});
}

/* this function is to get the event variables in tiny editor */
function getEventVariables(data){
	var get_id = data.value;
	jQuery.ajax({
		type: "GET",
		url: host+"api/events/"+get_id,
		headers:{"accept":"application/json"},
		success: function (result) {
            	parseEventVariables(result);
            }
        });
}

function parseEventVariables(x){
	y =  x.event.event_variables.map(function(row){
		return {'text' : row.name, 'value' : '{{'+row.key+'}}' } ;
	});
	eventVars = y;
	tinymce.activeEditor.destroy();
	tinymce.init(editorConfig);
}
var eventVars = [
{ text: 'First Name', value: '{{first_name}}' },
{ text: 'Last Name', value: '{{last_name}}' },
{ text: 'User Name', value: '{{username}}' },
{ text: 'Link', value: '{{link}}' },
{ text: 'Name', value: '{{name}}'},

];

var editorConfig = {
	selector: '#tinymceTextarea',
	height: 500,
	toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | mybutton',
	menubar: false,
	plugins: [
	'advlist autolink link anchor',
	'searchreplace code',
	'insertdatetime paste code'
	],
	setup: function (editor) {
		editor.addButton('mybutton', {
			type: 'listbox',
			text: 'Insert Variable',
			icon: false,
			onselect: function (e) {
				editor.insertContent(this.value());
			},
			values: eventVars,
		});
	},
	forced_root_block : '',
	content_css: ['//www.tinymce.com/css/codepen.min.css']
};

$(document).ready(function(){
	tinymce.init(editorConfig);
});


$(document).ready(function(){
/*On editing the email setting display the selected variable*/
	if ( $("#event-id").length ) {
	var get_id = $("#event-id").val();
		if(get_id !== ''){
			$.ajax({
				type: "GET",
				url: host+"api/events/"+get_id,
				headers:{"accept":"application/json"},
				success: function (result) {
					parseEventVariables(result);
				}
			});
		}

	}

	/*Ajax Setup for the loader*/
	$.ajaxSetup({
	    beforeSend: function() {
	        $('.loading').show();
	    },
	    complete: function() {
	        $('.loading').hide();
	    }
	});

});


/* this function Updates the user for there level */
    $('select[referral_lead_id]').change(function(){
        var referral_lead_id = $(this).attr('referral_lead_id');
        var value = $(this).val();
        updateTemplateData(referral_lead_id,value);
    });

    //function for handling user being idle 
	$(function(){
	    // Set idle time
	    if(typeof idleTime != "undefined" && idleTime != 0){
	        	$( document ).idleTimer(idleTime);
	        }

	});

	$(function(){
	    $( document ).on( "idle.idleTimer", function(event, elem, obj){
	        // function you want to fire when the user goes idle
	        if(typeof idleTime != "undefined" && idleTime != 0){
	        	window.location = host+'users/lockscreen';
	        }
	        
	   
	    });

	    $( document ).on( "active.idleTimer", function(event, elem, obj, triggerevent){
	        // function you want to fire when the user becomes active again
	    });

	});

//add vendor legacy reward
$("input[name=reward_id]").change(function(event){
        var rewardId = $(this).attr('reward-id');
        var id = $(this).attr('vendor-legacy-reward-id');
        if($(this).is(':checked'))
        {
        	var checkbox = $(this);
            $.ajax({
                method: "POST",
                url: host+"api/VendorLegacyRewards/add",
                headers:{"accept":"application/json"},
                //dataType: "json",
                contentType:"application/json",
                data:'{"legacy_reward_id" : '+rewardId+'}',
                success: function (result) {
                	checkbox.attr('vendor-legacy-reward-id',result.response.data.id);
					swal("Saved",'Legacy reward added', "success");;
				},
				error: function(){
					swal("Error",'Unable to save legacy reward', "error");	
				}
            });
        }else{
        	$.ajax({
                method: "DELETE",
                url: host+"api/VendorLegacyRewards/delete/"+id,
                headers:{"accept":"application/json"},
                //dataType: "json",
                contentType:"application/json",
                success: function (result) {
					swal("Updated",'Legacy reward removed', "success");;
				},
				error: function(){
					swal("Error",'Unable to update legacy reward', "error");	
				}
            });
        }
  });

function updateTemplateData(referral_lead_id, value){

    $.ajax({
    	method: "PUT",
    	url: host+"api/referral-leads/"+referral_lead_id,
        headers:{"accept":"application/json"},
        contentType: "application/json",
            data:'{"referral_lead_id" : '+referral_lead_id+',"vendor_referral_settings_id" : '+value+'}',
          })  
        .success(function(data){
        updateTemplateDataVal(data);
        });
        
}

function updateTemplateDataVal(res){
    if(res.response.data)
        console.log(res);
}

function changePoints(){
	$('.index.changepoints').on('change',function(){

		var id = $(this).attr('id');
		var points = $(this).val();
		console.log(points);
		if(!points)
		{	
			swal("Invalid",'Enter a number', "error");

		}else{
			$.ajax({
	  				method: "POST",
				    url: host+"api/VendorSurveyQuestions/edit/"+id,
				    headers:{"accept":"application/json"},
				    dataType: 'json',  
				    data:{
				  			"points": points
						 }
				  }).success(function(res){
					console.log(res.vendorSurveyQuestion.points);
				});
			
		}
	})
		

}


// //Update Staging To Live Mode

// function updateVendorSettingLive(id){
// 	jQuery.ajax({
// 		url: host+"api/vendor_settings/updateVendorSettingLive/"+id,
// 		headers:{"accept":"application/json"},
// 		dataType: 'json',
//             data:{
//             		"id" : id
//                 },
//             type: 'put',
//             //data: {get_template_id:get_id},
//             success: function (data) {
            	
//             }
//         });
// }




function updateVendorToLive(){
	swal({
        title: "Are you sure you want to switch to live mode?",
        text: "You will not be able to switch back to the demo mode and will have to log back in!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, switch!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (!isConfirm) return;

	jQuery.ajax({
		url: host+"api/vendors/sendVendorLive/",
		headers:{"accept":"application/json"},
		dataType: 'json',
            
            type: 'post',
            //data: {get_template_id:get_id},
            success: function (data) {
            	swal("Done!", "Updated To Live Mode", "success");
            	$( ".border-bottom .ibox-content" ).hide( "slow" );
            	window.location = host+'/users/logout';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal("Error!", "Please try again", "error");
    			//$( ".border-bottom .ibox-content" ).hide( "slow" );
            }
        });
	});
}

//standard gift coupon deletion
function confirmGiftCouponDeletion(giftCouponId){
	jQuery.ajax({
		url: host+"api/giftCoupons/checkForMilestoneRewards/"+giftCouponId,
		headers:{"accept":"application/json"},
		dataType: 'json',
            type: 'post',
            success: function (data) {
            	console.log(data);
            	swal({
						title: "Are you sure you want to delete the gift coupon?",
						text: data.response,
						type: "success",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Yes",
						closeOnConfirm: true
					}, function(isConfirm){
						  if(isConfirm) {
						    delGiftCoupon(giftCouponId);
						  }
            })
        },
            error: function (data) {
                swal("Error!", "Please try again", "error");
            }
});
}

function delGiftCoupon(giftCouponId){
	jQuery.ajax({
		url: host+"api/giftCoupons/delete/"+giftCouponId,
		headers:{"accept":"application/json"},
		dataType: 'json',
            type: 'post',
            success: function (data) {
            	console.log(data);
            	swal("Congrats!", "The gift coupon has been deleted", "success");
            	location.reload(true);
        },
            error: function (data) {
                swal("Error!", "Please try again", "error");
            }
	});
}

//instant gift coupon deletion
function confirmDelOfInstantGc(giftCouponId){
	swal({
						title: "Are you sure you want to delete the gift coupon?",
						// text:,
						type: "success",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Yes",
						closeOnConfirm: true
					}, function(isConfirm){
						  if(isConfirm) {
						    delGiftCoupon(giftCouponId);
						  }
            })
}

    //confirm if the patient wants to redeem an instant gift coupon or not
function confirmRedemption(redeemersId, giftCouponId, vendorId){
    swal({
            title: "Are you sure you want to redeem the gift coupon?",
            // text:,
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: true
        }, function(isConfirm){
            if(isConfirm) {
                redeemInstantGc(redeemersId, giftCouponId, vendorId);
        }
    })  
}

//redeem an instant gift coupon
function redeemInstantGc(redeemersId, giftCouponId, vendorId){
    var dataForPost = {
        "redeemer_peoplehub_identifier" : redeemersId,
        "redeemer_name" : "xyz",
        "gift_coupon_id" : giftCouponId,
        "vendor_id" : vendorId
    };
    console.log(dataForPost);

    jQuery.ajax({
        url: host+"api/awards/redeemInstantRewards",
        headers:{"accept":"application/json"},
        dataType: 'json',
        data: dataForPost,
        type: 'post',
        success: function (data) {
            console.log(data);
            swal({
                    title: "Congrats!", 
                    text: "The gift coupon has been awarded", 
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Okay",
                    closeOnConfirm: true
                    }, function(isConfirm){
                          if(isConfirm) {
                            $('#redeem').hide();
                            $('#thankyou').show();
                          }
                });
        },
        error: function (data) {
            console.log(data.responseJSON.message);
            swal("Error!", data.responseJSON.message+". Please try again.", "error");
        }
    });
}

function placeOrderOnAmazon(dataForPost){
	$('#amazon_place_order').attr('disabled', true);
	
	jQuery.ajax({
    url: host+"api/legacyRedemptions/placeAmazonOrder",
    headers:{"accept":"application/json"},
    dataType: 'json',
    data: dataForPost,
    type: 'post',
    success: function (data) {
    	if(data.success == "true"){
	        swal({
	                title: "Done!", 
	                text: "Redirect to cart?", 
	                type: "success",
	                showCancelButton: true,
	                confirmButtonColor: "#DD6B55",
	                confirmButtonText: "Okay",
	                closeOnConfirm: true
	                }, function(isConfirm){
	                      if(isConfirm) {
	                       	window.open(data.PurchaseURL);
	                      }
	            });
    	}else{
    		if(data.message !== "undefined" && data.message != ''){
    			swal("Error!", data.message, "error");
    		}else{
    			swal("Error!", "Unable to redeem product at the moment. Please try again later.", "error");
    		}
    	}
        $('#amazon_place_order').attr('disabled', false);
    },
    error: function (data) {
        swal("Error!", data+". Please try again.", "error");
        $('#amazon_place_order').attr('disabled', false);
    }

	});
}