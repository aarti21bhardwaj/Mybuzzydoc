<?php
$this->start('nav');
$firstName = \Cake\Utility\Inflector::humanize($sideNavData['first_name']);
?>

<?php $plan = [];
foreach ($vendorPlanFeatures as $key => $value) {
  $plan[$value['feature']['name']] = true;
}
?>
<?php $setting = [];
//pr($vendorSettings);die;
foreach ($vendorSettings as $key => $value) {
  if($value['setting_key']['type'] == 'boolean'){
    $setting[$value['setting_key']['name']] = $value['value'];
  }
//pr($setting); die;
}
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
              <span class="text-muted text-xs block">
                <?= $sideNavData['role_label']?>
                <b class="caret"></b>
              </span>
            </span>
          </a>
          <ul class="dropdown-menu animated fadeInRight m-t-xs">
            <li><?= $this->Html->link(__('Profile'), ['controller' => 'Users' ,'action' => 'edit' , $sideNavData['id']]) ?></li>
            <li class="divider"></li>
            <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
          </ul>
        </div>
        <div class="logo-element">
          <?= $this->Html->image('icon-reverse-low-rez.png', ['style' => 'width:30px; height:30px;', 'alt'=>'image'])?>
        </div>
      </li>
      <?php
      $controller = $this->request->params['controller'];
      $action = $this->request->params['action'];
      $mnu_dash = $mnu_setting = $mnu_report ='';

      $action;
      if ($controller == 'Users' && $action == 'dashboard') {
        $mnu_dash= "active";
      }
      else if (($controller == 'LegacyRewards') || ($controller == 'VendorLocations') || ($controller == 'ReferralTemplates') || ($controller == 'VendorReferralSettings') || ($controller == 'AuthorizeNetProfiles') || ($controller == 'Users') || ($controller == 'VendorPromotions') || ($controller == 'VendorEmailSettings') || ($controller == 'VendorSurveyQuestions') || ($controller == 'Tiers') || ($controller == 'TierPerks') || ($controller == 'GiftCoupons') ) {
        $mnu_setting = "active";
      }
      else if (($controller == 'VendorDepositBalances') || ($controller == 'CreditCardCharges') || ($controller == 'Reports') || ($controller == 'LegacyRedemptions') || ($controller == 'Referrals') || ($controller == 'ReferralLeads') || ($controller == 'ReviewRequestStatuses' )  ){
        $mnu_report = "active";
      }

      ?>
      <li class="<?php echo $mnu_dash; ?>">
        <a  href="<?php echo $this->Url->build(["controller" => "users","action" => "dashboard"]);?>#/"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
      </li>

      <li class="<?php echo $mnu_setting; ?>">
        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Settings </span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
         <li><?= $this->Html->link(__('Awarding Points'), ['controller'=>'SideNavigations','action' => 'awarding_points']) ?></li>
         <li><?= $this->Html->link(__('Programs'), ['controller'=>'SideNavigations','action' => 'programs']) ?></li>
         <li><?= $this->Html->link(__('Rewards'), ['controller'=>'SideNavigations','action' => 'rewards']) ?></li>
         <li><?= $this->Html->link(__('Referrals'), ['controller'=>'SideNavigations','action' => 'referrals']) ?></li>
         <li><?= $this->Html->link(__('Practice'), ['controller'=>'SideNavigations','action' => 'practice']) ?></li>

        </ul>
      </li>


    <li >
      <a  href="<?php echo $this->Url->build(["controller" => "SideNavigations","action" => "reports"]);?>#/"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Reports</span></a>
    </li>



    <?php 
    if(isset($setting['Training Videos']) && $setting['Training Videos']): ?>
    <li >
      <a  href="<?php echo $this->Url->build(["controller" => "TrainingVideos","action" => "index"]);?>#/"><i class="fa fa-youtube-play"></i> <span class="nav-label">Training Videos</span></a>
    </li>
    <?php endif; ?>
   
    </ul>
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
