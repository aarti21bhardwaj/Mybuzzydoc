<?php $setting = [];
//pr($vendorSettings);die;
foreach ($vendorSettings as $key => $value) {
  if($value['setting_key']['type'] == 'boolean'){
    $setting[$value['setting_key']['name']] = $value['value'];
  }
// pr($setting); die;
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
            <h2 class="text-navy font-bold"><strong>Programs</strong></h2>
            <h3 class="text-navy font-bold"><strong>No matter what your goals are, we have the rewards program option that will support them perfectly.</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Milestone Program</h2>
                <p class="m-xs">Reward your patients with a bonus when they complete a perfect score on the compliance survey.</p><br><br><br>
                <?php if(isset($plan['compliancesurvey']) && isset($setting['Milestone']) && $setting['Milestone']) {?>
                <?=$this->Html->link('EDIT', ['controller' => 'VendorMilestones', 'action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Tier Program</h2>
                <p class="m-xs">Set the tiers your patients will strive to obtain. Each tier will allow the patient to earn ‘cash back’ in points based on the amount they spend with your practice.</p><br><br>
                
           


                <?php if(isset($plan['tier']) && isset($setting['Tier Program']) && $setting['Tier Program'] && isset($setting['Tier Perks']) && $setting['Tier Perks']) {?>
                <?=$this->Html->link('EDIT', ['controller'=>'Tiers','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>&nbsp; &nbsp;
                <?=$this->Html->link('TIER PERKS', ['controller'=>'TierPerks','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } elseif(isset($plan['tier']) && isset($setting['Tier Program']) && $setting['Tier Program'] && ($setting['Tier Perks']==0)) {?>
                 <?=$this->Html->link('EDIT', ['controller'=>'Tiers','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>&nbsp; &nbsp;
                <?= $this->Form->label('', __('INACTIVE'),  ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Tier Perks are inactive for you. Contact BuzzyDoc to access this feature.']); ?>&nbsp; &nbsp;
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