<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2 align="center"><?= h($legacyRedemption->redeemer_name) ?></h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <dl class="dl-horizontal">
                                    <dt><?= __('Legacy Reward') ?>:</dt> <dd> <?= h($legacyRedemption->legacy_reward->name) ?> </dd>
                                    <dt><?= __('Practice') ?>:</dt> <dd> <?= h($legacyRedemption->vendor->id) ?> </dd>
                                    <dt><?= __('Redemptions Status') ?>:</dt> <dd> <?= h($legacyRedemption->legacy_redemption_status->name) ?> </dd>
                                    <dt><?= __('Redeemer Name') ?>:</dt> <dd> <?= h($legacyRedemption->redeemer_name) ?> </dd>
                                    <dt><?= __('Redeemer Peoplehub Identifier') ?>:</dt> <dd> <?= h($legacyRedemption->redeemer_peoplehub_identifier) ?> </dd>
                                    <dt><?= __('Id') ?>:</dt> <dd> <?= h($legacyRedemption->id) ?> </dd>
                                    <dt><?= __('Transaction Number') ?>:</dt> <dd> <?= h($legacyRedemption->transaction_number) ?> </dd>
                                </dl>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

