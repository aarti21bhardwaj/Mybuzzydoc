<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($legacyReward->name) ?></h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Name') ?>:</dt> <dd> <span class="label label-primary"><?= h($legacyReward->vendor->org_name) ?></span> </dd>
                        <dt><?= __('Reward Category') ?>:</dt> <dd> <?= h($legacyReward->reward_category->name) ?> </dd>
                        <dt><?= __('Product Type') ?>:</dt> <dd> <?= h($legacyReward->product_type->name) ?> </dd>
                            <dt><?=  h($legacyReward->points) ? 'Points' : 'Amount' ?>:</dt> <dd> <?= h($legacyReward->points) ? h($legacyReward->points) : h($legacyReward->amount) ?> </dd>
                        <dt><?= __('Image') ?>:</dt> <dd> <a href="<?= $legacyReward->image_url ?>" target="_blank"><?= $this->Html->image($legacyReward->image_url, array('height' => 100, 'width' => 100))?></a>  </dd>
                            <!-- <dt><?= __('Created') ?>:</dt> <dd> <?= h($legacyReward->created)?> </dd>
                            <dt><?= __('Modified') ?>:</dt> <dd><?= h($legacyReward->modified) ?></dd> -->
                            <dt><?= __('Status') ?>:</dt> <dd><?= h($legacyReward->status) ? 'Enabled' : 'Disabled' ?></dd>
                            <dt><?= __('Is Deleted') ?>:</dt> <dd><?= h($legacyReward->is_deleted) ? 'Yes' : 'No' ?></dd>
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
