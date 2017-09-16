<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($referralTier->name) ?></h2>
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <dl class="dl-horizontal">
                        <dt><?= __('Name') ?>:</dt> <dd> <?= h($referralTier->name) ?> </dd>
                        <dt><?= __('Referrals Required') ?>:</dt> <dd> <?= h($referralTier->referrals_required) ?> </dd>
                        <dt><?= __('Points') ?>:</dt> <dd> <?= h($referralTier->points) ?> </dd>
                        <dt><?= __('Gift Coupon') ?>:</dt> <dd> <?= $referralTier->referral_tier_gift_coupon ? h($referralTier->referral_tier_gift_coupon['gift_coupon']['description']) : 'No Coupon Selected' ?> </dd>
                    </dl>
                </div>
            </div> 
            
            <div class="row">
                <div class="col-lg-12 text-center">
                    <?= $this->Html->link('Back',$this->request->referer(),['class' => ['btn', 'btn-warning']]);?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

