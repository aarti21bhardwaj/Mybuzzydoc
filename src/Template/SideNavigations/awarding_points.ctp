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
            <h2 class="text-navy font-bold"><strong>Awarding Points</strong></h2>
            <h3 class="text-navy font-bold"><strong>This page provides your office with several ways to offer patients rewards for health and marketing.</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Practice Promotions</h2>
                <p class="m-xs">These ‘Ways to Earn’ can be customized to fit your everyday practice needs.</p><br><br><br>
                <?=$this->Html->link('EDIT', ['controller' => 'VendorPromotions', 'action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Compliance Survey</h2>
                <p class="m-xs">These questions will appear on each patient's account as an easy way for staff to track and record points for compliance.</p><br><br>
                <?=$this->Html->link('EDIT', ['controller' => 'VendorSurveyQuestions', 'action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
           
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Manual Points</h2>
                <p class="m-xs"> Easily record custom points on patient accounts along with their notes.</p><br><br><br>
                <?php if(isset($setting['Manual Points']) && $setting['Manual Points']) {?>
                <?= $this->Form->label('', __('ACTIVE'), ['class' => ['badge-primary', 'btn-w-m btn-rounded'], 'style' => 'padding-top:7px; padding-bottom:5px;']); ?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Amount Spent</h2>
                <p class="m-xs">When running a Tier Program you will use this feature to record patient points.</p><br><br>
                <?php if(isset($plan['tier']) && isset($setting['Tier Program']) && $setting['Tier Program']) {?>
                <?= $this->Form->label('', __('ACTIVE'), ['class' => ['badge-primary', 'btn-w-m btn-rounded'], 'style' => 'padding-top:7px; padding-bottom:5px;']); ?>
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