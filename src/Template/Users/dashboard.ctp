<!-- <div class="row" ng-app="dashboardApp">
	<div ng-controller="MenuController as MenuCtrl">
		<div ng-show="showMenu">
			<div class="btn-group" >
			    <a href="#!/patient" ng-show="auth('awardPoints')"><button ng-class="getClass('/patient')" class="btn" type="button">Award Points</button></a>
			    <a href="#!/redeem"><button ng-class="getClass('/redeem')" class="btn" type="button">Redeem</button></a>
			    <a href="#!/activityHistory" ng-show="showActivityHistory || showReferralHistory"><button ng-class="getClass('/activityHistory')" class="btn" type="button">Activity History</button></a>
			</div>
			<div class="btn-group">
			    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false">More<span class="caret"></span></button>
			    <ul class="dropdown-menu">
			        <li><a href="#!/requestReview">Request Review</a></li>
			        <li><a href="#!/editProfile">Edit Profile</a></li>
			        <li ng-if="auth('referrals')"><a href="#!/addLead">Add Lead</a></li> -->
			        <!-- <li ng-if="!showOldHistory"><a href="#!/oldBuzzyDocHistory">Old BuzzyDoc History</a></li> -->
			        <!-- <li><a href="#!/">Lost Card</a></li> -->
			        <!-- <li class="divider"></li>
			        <li><a href="#!/">Separated link</a></li> -->
			   <!--  </ul>
			</div>
			<div class="btn-group" ng-if="showWelcome">
				<button class="btn btn-sm btn-success" ng-click = "sendWelcomeEmail()" type="button">
					<i class="fa fa-envelope"></i> Send Welcome Email
				</button>
				&nbsp;
				<i class="fa fa-lg fa-info-circle" title="This is a new patient that you have yet to send a welcome email. Click the button to send a welcome email."></i>
			</div>
			<div class="pull-right btn-group">
		    	<a href="#!/"><button class="btn btn-primary" ng-click="clearPatientData()" type="button">Back to Search</button></a>
	    	</div>
		</div> -->
	
		<!-- Angular App -->
		<!-- <div ng-view></div>
	</div>
</div> -->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<script>
<?php
    echo 'var vendorId = "'.$vendorId.'";';
?>

$(document).ready(function(){
    topNavSearch = $('#searchText').val();
        $('#searchText').val("");
        
        if(initialised != 1){
            $('#pageTitle').html("Dashboard");
            $('#breadcrumb').html("");
            //dashboard template present in topnav
            $('#pageWrapper').html(document.getElementById('template-id').innerHTML);
            

            angular.element(document).ready(function(){


                try {
                    // angular.module('dashboardApp');
                    angular.bootstrap(document, ['dashboardApp']);
                } 
                catch(e) {
                    console.log(!e.message.indexOf('btstrpd'))
                }
            });
            initialised = 1;
        }

        
        return false;
});
</script>