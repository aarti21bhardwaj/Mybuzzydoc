<div class = "row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md">
                                <h2><?= h('Referral Tier Perk') ?></h2>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Referral Tier') ?>:</dt> <dd> <?= h($referralTierPerk->referral_tier['name']) ?> </dd>
                            <dt><?= __('Perk') ?>:</dt> <dd> <?= h($referralTierPerk->perk) ?> </dd>
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