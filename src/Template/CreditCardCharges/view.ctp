<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <!-- <h2><?= h($vendor->org_name) ?></h2> -->
                        </div>
                        <dl class="dl-horizontal">
                            <dt><?= __('Org Name') ?>:</dt> <dd><span class="label label-primary"><?= h($creditCardCharge->vendor->org_name) ?></span></dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Auth Code') ?>:</dt> <dd> <?= h($creditCardCharge->auth_code) ?> </dd>
                        <dt><?= __('Transactionid') ?>:</dt> <dd> <?= h($creditCardCharge->transactionid) ?> </dd>
                        <dt><?= __('Response Code') ?>:</dt> <dd> <?= h($creditCardCharge->response_code) ?> </dd>
                        <dt><?= __('Amount') ?>:</dt> <dd> <?= $this->Number->format($creditCardCharge->amount) ?> </dd>
                        <dt><?= __('Transaction Fee') ?>:</dt> <dd> <?= $this->Number->format($creditCardCharge->transaction_fee) ?> </dd>
                        <dt><?= __('Description') ?>:</dt> <dd> <?= h($creditCardCharge->description) ?> </dd>
                        <dt><?= __('Reason') ?>:</dt> <dd> <?= h($creditCardCharge->reason) ?> </dd>
                        <dt><?= __('Created') ?>:</dt> <dd> <?= h($creditCardCharge->created) ?> </dd>
                        <dt><?= __('Modified') ?>:</dt> <dd><?= h($creditCardCharge->modified) ?></dd>
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