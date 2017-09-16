//JS for Facebook Share Button
$(document).ready(function(){
    $('#shareBtn').click(function() {
        FB.ui(
            {
                method: 'share',
                href: publicUrl,
            },
            // callback
            function(response) {
                if(response && !response.error_message){
                    $.ajax({
                        url: urlForNotify,
                        headers:{"accept":"application/json"},
                        dataType: 'json',
                        data: {
                            "fb": true,
                            "vendorReviewId" : vendorReviewId
                        },
                        type: 'post',
                        success: function(res){  
                            console.log(res);
                            console.log('success');
                            swal('Points Awarded', "", "success");
                        },
                        error: function(err) {
                            if(err.status == 400)
                                swal(err.message, "", "error");
                            console.log(err);          
                        }
                    });
                    
                }else{

                    swal('Not Posted', "", "error");
                }
            }
        );
    });

    //JS for copying Review Button
    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        if(e.action=='copy'){
            alert('Review Copied');
        }
    });    

    //JS for notifying Clinic Button
    $('#notifyClinic').click(function(notify) { 
        
        gplus=  + $('#gplus').prop('checked');        
        yelp= + $('#yelp').prop('checked');
        ratemd=  + $('#ratemd').prop('checked');
        yahoo=  + $('#yahoo').prop('checked');
        healthgrades= + $('#healthgrades').prop('checked');

        if(isNaN(gplus))
            gplus = 0;
        if(isNaN(yelp))
            yelp = 0;
        if(isNaN(ratemd))
            ratemd = 0;
        if(isNaN(yahoo))
            yahoo = 0;
        if(isNaN(healthgrades))
            healthgrades = 0;
        
        if(!gplus && !yelp && !ratemd && !yahoo && !healthgrades)
        {
            $('#resp').text('No Options Selected').css('font-weight', 'bold');
        }else{


            $.ajax({
                url: urlForNotify,
                headers:{"accept":"application/json"},
                dataType: 'json',
                data: {
                    "gplus" : gplus,
                    "yelp" : yelp,
                    "ratemd" : ratemd,
                    "yahoo" : yahoo,
                    "healthgrades" : healthgrades,
                    "vendorReviewId" : vendorReviewId,
                    "fb": false, 
                },
                type: 'post',
                success: function(res){  
                    console.log(res);
                    if(res.response.already_notified == true)
                    {
                        $('#modal-body').text('Clinic has already been Notified').css('font-weight', 'bold'); 
                        $("#notifyClinic").remove();   
                    
                    }else{


                        $('#modal-body').text('Clinic has been Notified').css('font-weight', 'bold');
                        $("#notifyClinic").remove();
                    }
                },
                error: function(err) {
                    console.log(err);
                    $('#resp').text('There was some error in notifying').css('font-weight', 'bold');         
                }
            });
        }        
    });

});