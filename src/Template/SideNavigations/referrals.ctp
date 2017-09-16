<?php $setting = [];
//pr($vendorSettings);die;
foreach ($vendorSettings as $key => $value) {
  if($value['setting_key']['type'] == 'boolean'){
    $setting[$value['setting_key']['name']] = $value['value'];
  }
//pr($setting); die;
}
?>

<?php $plan = [];
foreach ($vendorPlanFeatures as $key => $value) {
  $plan[$value['feature']['name']] = true;
} ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl">
                <h2 class="text-navy font-bold"><strong>Referrals</strong></h2>
                <h3 class="text-navy font-bold"><strong>Never lose a lead again. Our referral system makes managing patient recommendations easier than ever.</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Referral Settings</h2>
                <p class="m-xs">Manage your different types of referrals, the points associated with each and your messaging option.</p><br><br>


                <?php if(isset($setting['Referrals']) && $setting['Referrals']) {?>
                <?=$this->Html->link('EDIT TEMPLATES', ['controller' => 'ReferralTemplates', 'action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?= $this->Html->link(__('EDIT SETTINGS'), ['controller'=>'VendorReferralSettings','action' => 'index'], ['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>

                
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Referral Tier Program</h2>
                <p class="m-xs">Accelerate patient incentives and earnings by implementing a tiered referral program in your office.</p><br><br>

                <?php if(isset($setting['Referral Tier Program']) && $setting['Referral Tier Program']) {?>
                <?=$this->Html->link('EDIT TIERS', ['controller' => 'ReferralTiers', 'action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?= $this->Html->link(__('TIER PERKS'), ['controller'=>'ReferralTierPerks','action' => 'index'], ['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .content{
        height:300px;
        font-size: 15px;
    }
</style>