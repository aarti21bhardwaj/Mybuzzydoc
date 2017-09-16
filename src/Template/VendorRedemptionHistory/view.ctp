<div class = "row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md">

                            </div>
                            <dl class="dl-horizontal">
                            <dt><?= __('Practice Name') ?>:</dt> 
                            <dd>
                                <span class="label label-primary"><?= h($vendorRedemptionHistory->vendor->org_name) ?></span>
                            </dd>
                            </dl> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('Cc Transaction Identifier') ?>:</dt> <dd> <?= h($vendorRedemptionHistory->cc_transaction_identifier) ?> </dd>
                                <dt><?= __('Actual Balance') ?>:</dt> <dd> <?= $this->Number->format($vendorRedemptionHistory->actual_balance) ?> </dd>
                                <dt><?= __('Redeemed Amount') ?>:</dt> <dd> <?= $this->Number->format($vendorRedemptionHistory->redeemed_amount) ?> </dd>
                                <dt><?= __('Remaining Amount') ?>:</dt> <dd> <?= $this->Number->format($vendorRedemptionHistory->remaining_amount) ?> </dd>
                                <dt><?= __('Cc Charged Amount') ?>:</dt> <dd> <?= $this->Number->format($vendorRedemptionHistory->cc_charged_amount) ?> </dd>
                                <dt><?= __('Created') ?>:</dt> <dd> <?= h($vendorRedemptionHistory->created) ?> </dd>
                                <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendorRedemptionHistory->modified) ?></dd>
                                <dt><?= __('Status') ?>:</dt> <dd><?= h($vendorRedemptionHistory->status)?'Enabled':'Disabled' ?></dd>
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