<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary"><i class="fa fa-bars"></i> </a>

                    <form role="search" class="navbar-form-custom" id="<?php echo ($sideNavData['role_name']=="admin")? 'searchPatient':'searchForm'?>" action="<?php echo ($sideNavData['role_name']=="admin")? $this->Url->build(['controller'=>'Admin','action'=>'patient-search']):'#' ?>">
                        <div class="form-group">
                            <input type="text" placeholder="Search for a Patient" id="searchText" class="form-control">
                        </div>
                    </form>
                    <script>
                        <?php
                        if(isset($sideNavData['role_name']) && $sideNavData['role_name']!="admin" && $topSearch):
                            echo 'var vendorId = "'.$topHeader['vendor_id'].'";';
                        endif
                        ?>
                    </script>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <?php if(isset($topHeader['vendor_id']) && $topHeader['vendor_id'] && $topSearch):?>
                <li><span class="m-r-sm"><strong><?= $topHeader['org_name'] ?></strong></span></li>
            <?php endif; ?>
            <li>
             <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['class' => ['fa', 'fa-sign-out']]) ?>
            </li>
        </ul>
  </nav>
<?php
if((isset($topHeader['value']) && !$topHeader['value']) || (isset($cardSetup) && !$cardSetup)): ?>

<div class="ibox-content">
    <?php if((isset($topHeader['value']) && !$topHeader['value'])):?>
        <div class="alert alert-info">
            <?= __('SWITCH_TO_LIVE_MESSAGE', ucfirst($topHeader['org_name'])) ?>
            <?php if($topHeader['role_id'] == '2'):?>
            <a class="alert-link" href="javascript:void(0);" onclick="updateVendorToLive()">Switch To Live</a>.
            <?php endif;?>
        </div>
    <?php endif;?>

    <?php if((isset($cardSetup) && !$cardSetup) && (isset($topHeader['role_id']) && !$topHeader['role_id'] != 1)):?>
            <div class="alert alert-info" onclick="this.classList.add('hidden');">
                <?= __('SETUP_CARD') ?>
            </div>
    <?php endif;?>
</div>
<?php endif;?>

<script type="text/template" id="template-id">
    <div class="row" ng-app="dashboardApp">
        <div ng-controller="MenuController as MenuCtrl" ng-cloak>
            <div ng-show="showMenu">
                <div class="btn-group" >
                    <a href="#!/patient" ng-show="auth('awardPoints')">
                        <button ng-class="getClass('/patient')" class="btn" type="button">
                            Award Points
                        </button>
                    </a>
                    <a href="#!/redeem" ng-if="allowRedemptions">
                        <button ng-class="getClass('/redeem')" class="btn" type="button">
                            Redeem
                        </button>
                    </a>
                    <a href="#!/activityHistory" ng-show="showActivityHistory || showReferralHistory">
                        <button ng-class="getClass('/activityHistory')" class="btn" type="button">
                            Activity History
                        </button>
                    </a>
                    <a href="#!/patientAssessment" ng-show="auth('assessmentSurveys')">
                        <button ng-class="getClass('/patientAssessment')" class="btn" type="button">
                            Patient Assessment
                        </button>
                    </a>
                </div>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false">
                        More<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#!/requestReview">Request Review</a>
                        </li>
                        <li>
                            <a href="#!/editProfile">Edit Profile</a>
                        </li>
                        <li ng-if="auth('referrals')">
                            <a href="#!/addLead">Add Lead</a>
                        </li>
                        <li ng-if="showOldHistory">
                            <a href="#!/oldBuzzyDocHistory">Old BuzzyDoc History</a>
                        </li>
                        <li ng-click = "sendForgotPasswordLink()">
                            <a>Send Reset Password Link</a>
                        </li>
                        <li ng-if="auth('florist')">
                            <a ng-click="sendFlowerValidation()">Send Flower</a>
                        </li>
                        <li>
                            <a href="#!/patientAddress">Add/Update Patient Address</a>
                        </li>
                        <li>
                            <a ng-click="confirmDeletePatient()">Delete Patient</a>
                        </li>
                    </ul>
                </div>
                <div class="btn-group" ng-if="showWelcome">
                    <button class="btn btn-sm btn-success" ng-click = "sendWelcomeEmail()" type="button">
                        <i class="fa fa-envelope"></i> Send Welcome Email
                    </button>
                    &nbsp;
                    <i class="fa fa-lg fa-info-circle" title="This is a new patient that you have yet to send a welcome email+ Click the button to send a welcome email+"></i>
                </div>
                <div class="instantRewardButton instantRewardButtonCont" ng-if="showInstantRewardsButton()" ng-include src="instantButtonUrl" ng-click="redirectToInstantRewards()"></div>
                <div class="pull-right">
                    <ul class="list-inline">
                        <li>
                            <div>
                                <h3 class="label label-success" ng-if="patientData != false">{{patientData.name}}</h3>
                                &nbsp;
                                <strong>Point Balance:</strong> {{pointBalance ? pointBalance : 0}}
                                <strong>Dollar Value:</strong> {{pointBalance ? (pointBalance/50 | currency : '$' : 2) : 0}}
                            </div>
                        </li>
                        <li>
                           &nbsp;&nbsp;&nbsp;&nbsp;
                        </li>
                        <li>
                            <div class="btn-group">
                                <a href="#!/">
                                    <button class="btn btn-primary" ng-click="clearPatientData()" type="button">
                                        Back to Search
                                    </button>
                                </a>
                            </div>
                        </li>
                         <li>
                            <div>
                                <button class="btn btn-primary startTour" type="button" ng-click="startTour()">
                                    <i class="fa fa-play"></i> Start Tour
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div><!-- Angular App -->
            <div>
                <div ng-view></div>
            </div>
        </div>
    </div>
</script>
<script type="text/javascript">
    var topNavSearch = "";
    var initialised = 0;
    $("#searchForm").submit(function(){


        topNavSearch = $('#searchText').val();
        $('#searchText').val("");

        if(initialised != 1){
            $('#pageTitle').html("Dashboard");
            $('#breadcrumb').html("");
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
