<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Vendor Name') ?>:</dt> <dd><span class="label label-primary"><?= h($referral->vendor->org_name) ?></span></dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Refer From') ?>:</dt> <dd> <?= h($referral->refer_from) ?> </dd>
                            <dt><?= __('Refer To') ?>:</dt> <dd> <?= h($referral->refer_to) ?> </dd>
                            <dt><?= __('First Name') ?>:</dt> <dd> <?= h($referral->first_name) ?> </dd>
                            <dt><?= __('Last Name') ?>:</dt> <dd> <?= h($referral->last_name) ?> </dd>
                            <dt><?= __('Phone') ?>:</dt> <dd> <?= h($referral->phone) ?> </dd>
                            <dt><?= __('Refered On') ?>:</dt> <dd><?= h($referral->created) ?></dd>
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