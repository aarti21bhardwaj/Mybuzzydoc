<div class="row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md">
                                <h2></h2>
                            </div>
                            <dl class="dl-horizontal">
                            <dt><?= __('Practice Name') ?>:</dt> 
                                <dd>
                                    <span class="label label-primary">
                                        <?= h($vendorReferralSetting->vendor->org_name) ?>
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('Referral Level Name') ?>:</dt> <dd> <?= h($vendorReferralSetting->referral_level_name) ?> </dd>
                                <dt><?= __('Referrer Award Points') ?>:</dt> <dd> <?= h($vendorReferralSetting->referrer_award_points) ?> </dd>
                                <dt><?= __('Referree Award Points') ?>:</dt> <dd> <?= h($vendorReferralSetting->referree_award_points) ?> </dd>
                                <dt><?= __('Created') ?>:</dt> <dd><?= h($vendorReferralSetting->created) ?></dd>
                                <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendorReferralSetting->modified) ?></dd>
                                <dt><?= __('Status') ?>:</dt> <dd><?= h($vendorReferralSetting->status)?'Enabled':'Disabled' ?></dd>
                                <dt><?= __('Gift Coupon') ?>:</dt> <dd> <?= $vendorReferralSetting->referral_setting_gift_coupon == null ? 'No Coupon Selected' : h($vendorReferralSetting['referral_setting_gift_coupon']['gift_coupon']['description']) ?> </dd>
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
</div>