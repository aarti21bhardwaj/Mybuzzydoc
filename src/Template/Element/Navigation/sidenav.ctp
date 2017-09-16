<?php
$this->start('nav');
$firstName = \Cake\Utility\Inflector::humanize($sideNavData['first_name']);
?>

<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element">
          <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
            <span class="clear"> 
              <span class="block m-t-xs"> 
                <strong class="font-bold">
                  <h2><?= $firstName ?></h2>
                </strong>
              </span> 
              <span class="text-muted text-xs block"><?= $sideNavData['role_label']?> <b class="caret"></b></span> </span> </a>
              <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li><?= $this->Html->link(__('Profile'), ['controller' => 'Users', 'action' => 'edit', $sideNavData['id']]) ?></li>
                <li class="divider"></li>
                <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
              </ul>
            </div>
            <div class="logo-element">
              <?= $this->Html->image('icon-reverse-low-rez.png', ['style' => 'width:30px; height:30px;', 'alt'=>'image'])?>
            </div>
          </li>
<!--  <li>
<a href="<?php echo $this->Url->build(["controller" => "users","action" => "dashboard"]);?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></span></a>
</li> --> 
                          <!-- CLIENTS -->
  <li>
      <a href="<?php echo $this->Url->build(["controller" => "Vendors","action" => "index"]);?>#/"><i class="fa fa-th-large"></i> <span class="nav-label">Clients</span></a>
  </li>

                        <!-- Admin Settings -->
  <li class="">
    <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Admin Settings </span><span class="fa arrow"></span></a>
      <ul class="nav nav-second-level collapse">
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "users","action" => "index"]);?>#/"><span class="nav-label">Staff Users</span></a>
        </li>
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "Roles","action" => "index"]);?>#/"><span class="nav-label">Roles</span></a>
        </li>
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "Promotions","action" => "index"]);?>#/"></i> <span class="nav-label">Default Promotions</span></a>
        </li>
        <li>
          <a href="#">Rewards <i class="fa arrow"></i></a>
          <ul class="nav nav-third-level">
            <li>
              <a href="<?php echo $this->Url->build(["controller" => "LegacyRewards","action" => "index"]);?>#/"> <span class="nav-label">In-House Rewards</span></a>
            </li>

            <li>
              <a href="<?php echo $this->Url->build(["controller" => "GiftCoupons","action" => "index"]);?>#/"><span class="nav-label">Gift Coupons</span></a>
            </li>
          </ul>
        </li>
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "EmailSettings","action" => "index"]);?>#/"><span class="nav-label">Emails</span></a>
        </li>

        <li>
          <a href="#">Complience Survey<i class="fa arrow"></i></a>
          <ul class="nav nav-third-level">
            <li>
              <a href="<?php echo $this->Url->build(["controller" => "Surveys","action" => "index"]);?>#/"><span class="nav-label">Survey Names</span></a>
            </li>

            <li>
              <a href="<?php echo $this->Url->build(["controller" => "Questions","action" => "index"]);?>#/"><span class="nav-label">All Survey Questions</span></a>
            </li>

            <li>
              <a href="<?php echo $this->Url->build(["controller" => "SurveyQuestions","action" => "index"]);?>#/"><span class="nav-label">Assign Survey Questions</span></a>
            </li>
          </ul>
        </li>
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "AssessmentSurveys","action" => "index"]);?>#/"></i> <span class="nav-label">Assessment Surveys</span></a>
        </li>
        <li class="">
          <a href="#">Tier Program <i class="fa arrow"></i></a>
          <ul class="nav nav-third-level">
            <li><?= $this->Html->link(__('Tiers'), ['controller'=>'Tiers','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Tier Perks'), ['controller'=>'TierPerks','action' => 'index']) ?></li>
          </ul>
        </li>
        <li class="">
          <a href="#">Referral Tier Program <i class="fa arrow"></i></a>
          <ul class="nav nav-third-level">
            <li><?= $this->Html->link(__('Referral Tiers'), ['controller'=>'ReferralTiers','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Referral Tier Perks'), ['controller'=>'ReferralTierPerks','action' => 'index']) ?></li>
          </ul>
        </li>
        <li class="">
          <a href="#">Referral Settings<i class="fa arrow"></i></a>
          <ul class="nav nav-second-level">
            <li><?= $this->Html->link(__('Referral Templates'), ['controller'=>'ReferralTemplates','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Vendor Referral Settings'), ['controller'=>'VendorReferralSettings','action' => 'index']) ?></li>
          </ul>
        </li>
        <li>
          <a href="<?php echo $this->Url->build(["controller" => "TrainingVideos","action" => "index"]);?>#/"><span class="nav-label">Training Videos</span></a>
        </li>
        <li>
         <a href="#"><span class="nav-label">Cards </span><span class="fa arrow"></span></i></a>
          <ul class="nav nav-second-level collapse">
            <li><?= $this->Html->link(__('Add Card Series'), ['controller'=>'CardSeries','action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('View Card Series'), ['controller'=>'CardSeries','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('View New Card Requests'), ['controller'=>'VendorCardRequests','action' => 'index']) ?></li>
          </ul>
        </li>
       <li>
        <a href="<?php echo $this->Url->build(["controller" => "Templates","action" => "index"]);?>#/"><span class="nav-label">Industry Templates</span></a>
      </li>
      </ul>
  </li>
    
                <!-- Client Settings -->
  <li class = "">
  <a href="#"><i class="fa fa-keyboard-o"></i> <span class="nav-label">Client Settings</span><span class="fa arrow"></span></i></a>
    <ul class="nav nav-second-level">
      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorLocations","action" => "index"]);?>#/"></i> <span class="nav-label">Locations</span></a>
      </li>
        
      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorDocuments","action" => "index"]);?>#/"><span class="nav-label">Documents</span></a>
      </li>

      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorEmailSettings","action" => "index"]);?>#/"><span class="nav-label">Client Emails</span></a>
      </li>

      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorPromotions","action" => "index"]);?>#/"><span class="nav-label">Client Promotions</span></a>
      </li>

      <li>
        <a href="#">Client Complience Survey<i class="fa arrow"></i></a>
          <ul class="nav nav-third-level">
            <li>
              <a href="<?php echo $this->Url->build(["controller" => "VendorSurveys","action" => "index"]);?>#/"><span class="nav-label">All Client Surveys</span></a>
            </li>
            <li>
              <a href="<?php echo $this->Url->build(["controller" => "VendorSurveyQuestions","action" => "index"]);?>#/"><span class="nav-label">All Survey Questions</span></a>
            </li>
          </ul>
      </li>
       
      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorAssessmentSurveys","action" => "index"]);?>#/"><span class="nav-label">Client Assessment Surveys</span></a>
      </li>

      <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorMilestones","action" => "index"]);?>#/"><span class="nav-label">Client Milestones</span></a>
      </li>

       <li>
        <a href="<?php echo $this->Url->build(["controller" => "VendorInstantGiftCouponSettings","action" => "index"]);?>#/"><span class="nav-label">Instant Rewards Settings</span></a>
      </li> 
                    
    </ul>
</li>

<li class = "">
  <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Reports </span><span class="fa arrow"></span></i></a>
  <ul class="nav nav-second-level collapse">
    <li>
       <a href="#"><span class="nav-label">Admin Reports</span><span class="fa arrow"></span></i></a>
        <ul class="nav nav-second-level collapse">
          <li>
            <a href="#">Quick View <i class="fa arrow"></i></a>
            <ul class="nav nav-third-level">
              <li><?= $this->Html->link(__('Average Points Awarded'), ['controller'=>'QuickReports','action' => 'index']) ?></li> 
              <li><?= $this->Html->link(__('Average # of Patients Receiving Points'), ['controller'=>'QuickReports','action' => 'patients']) ?></li>
            </ul>
          </li>

          <li><?= $this->Html->link(__('Client Deposit Balance'), ['controller'=>'VendorDepositBalances','action' => 'index']) ?></li>

          <li><?= $this->Html->link(__('Credit Card Charge'), ['controller'=>'CreditCardCharges','action' => 'index']) ?></li>

        </ul>
    </li>

   


   <li>
       <a href="#"><span class="nav-label">Client Reports</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
        
              <li>
                <?= $this->Html->link(__('Redemptions'), ['controller' => 'LegacyRedemptions', 'action' => 'index'])?>
              </li> 
              <li>
                <?= $this->Html->link(__('Vendor Reviews'), ['controller' => 'ReviewRequestStatuses', 'action' => 'index']) ?>
              </li>

              <li>
              <a href="#">Referrals <i class="fa arrow"></i></a>
              <ul class="nav nav-third-level">
                <li><?= $this->Html->link(__('Referred People'), ['controller'=>'Referrals','action' => 'index']) ?></li> 
                <li><?= $this->Html->link(__('Referral Leads'), ['controller'=>'ReferralLeads','action' => 'index']) ?></li>
              </ul>
            </li>
          
        </ul>
    </li>
  </ul>
</li>
    
<li >
</div>
</nav>


<?php 
$this->end();

$this->Html->scriptStart(['block' => 'scriptBottom']);
echo "$(function () {
  $('#side-menu').metisMenu();
});";
$this->Html->scriptEnd();

?>


