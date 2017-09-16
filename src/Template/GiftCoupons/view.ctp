<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($giftCoupon->name) ?></h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Name') ?>:</dt> <dd> <span class="label label-primary"><?= h($giftCoupon->vendor->org_name) ?></span> </dd>
                        <dt><?= __('Points') ?>:</dt> <dd> <?= $this->Number->format($giftCoupon->points) ?> </dd>
                        <dt><?= __('Id') ?>:</dt> <dd> <?= $this->Number->format($giftCoupon->id) ?></dd>
                        <dt><?= __('Description') ?>:</dt> <dd> <?= h($giftCoupon->description) ?></dd>
                        <dt><?= __('Expiry Duration') ?>:</dt> <dd> <?= $this->Number->format($giftCoupon->expiry_duration) ?></dd>
                            <dt><?= __('Created') ?>:</dt> <dd> <?= h($giftCoupon->created)?> </dd>
                            <dt><?= __('Modified') ?>:</dt> <dd><?= h($giftCoupon->modified) ?></dd>
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
