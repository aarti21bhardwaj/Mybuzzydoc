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
            <h2 class="text-navy font-bold"><strong>Rewards</strong></h2>
            <h3 class="text-navy font-bold"><strong>Keep patients invested in your practice with our expansive range of prize options. There is no limit to how you can reward your patients.</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Express Gifts</h2>
                <p class="m-xs">Gift an array of prizes to patients without using their rewards points.</p><br><br><br>
                <?php if(isset($setting['Express Gifts']) && $setting['Express Gifts']) {?>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'LegacyRewards','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD NEW', ['controller'=>'LegacyRewards','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">In-House Rewards</h2>
                <p class="m-xs">Make product or service rewards options available to your patients to redeem their points for.</p><br><br>
                <?php if(isset($setting['Products And Services']) && $setting['Products And Services']) {?>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'LegacyRewards','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD NEW', ['controller'=>'LegacyRewards','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
    <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Instant Rewards</h2>
                <p class="m-xs">Automatically give your patients a reward when they hit a specified spending threshold. Expirations can be set.</p><br><br>
                <?php if(isset($plan['instantreward']) && isset($setting['Instant Gift Coupons']) && $setting['Instant Gift Coupons']) {?>
                <?=$this->Html->link('EDIT', ['controller'=>'VendorInstantGiftCouponSettings','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
           
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Gift Coupons</h2>
                <p class="m-xs">Patients can redeem their points for Dollar Off Certificates, a % Discount or a Free Service.</p><br><br>
                <?php if(isset($plan['giftcoupons']) && isset($setting['Gift Coupons']) && $setting['Gift Coupons']) {?>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'GiftCoupons','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD NEW', ['controller'=>'GiftCoupons','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
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