<div class = "row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('First Name') ?>:</dt> <dd> <?= h($referralLead->first_name) ?> </dd>
                                <dt><?= __('Last Name') ?>:</dt> <dd> <?= h($referralLead->last_name) ?> </dd>
                                <?php if($referralLead->parent_name): ?>
                                    <dt><?= __("Parent's Name") ?>:</dt> <dd> <?= h($referralLead->parent_name) ?> </dd>
                                <?php endif; ?>
                                <dt><?= __('Email') ?>:</dt> <dd> <?= h($referralLead->email ? $referralLead->email : 'No Email is set') ?> </dd>
                                <dt><?= __('Phone') ?>:</dt> <dd> <?= h($referralLead->phone ? $referralLead->phone : 'No Phone is set') ?> </dd>
                                <dt><?= __('Preferred Talking Time')?>:</dt><dd><?= h($referralLead->preferred_talking_time) ?></dd>
                                <?php if($referralLead->notes): ?>
                                    <dt><?= __("Notes") ?>:</dt> <dd> <?= h($referralLead->notes ) ?> </dd>
                                <?php endif; ?>
                                <dt><?= __('Referral Level') ?>:</dt> <dd> <?= $referralLead->has('vendor_referral_setting') ? $this->Html->link($referralLead->vendor_referral_setting->referral_level_name, ['controller' => 'VendorReferralSettings', 'action' => 'view', $referralLead->vendor_referral_setting->id]) : '' ?> </dd>


                                <dt><?= __('Vendor') ?>:</dt> <dd> <?= $referralLead->has('vendor') ? $this->Html->link($referralLead->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $referralLead->vendor->id]) : '' ?> </dd>
                                <dt><?= __('Added On') ?>:</dt> <dd><?= h($referralLead->created) ?></dd>
                                <dt><?= __('Status') ?>:</dt> <dd><?= h($referralLead->referral_status->status)?></dd>
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