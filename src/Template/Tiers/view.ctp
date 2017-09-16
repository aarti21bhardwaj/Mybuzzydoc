<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($tier->name) ?></h2>
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <dl class="dl-horizontal">
                        <dt><?= __('Name') ?>:</dt> <dd> <?= h($tier->name) ?> </dd>
                        <dt><?= __('Lowerbound') ?>:</dt> <dd> <?= h($tier->lowerbound) ?> </dd>
                        <dt><?= __('Upperbound') ?>:</dt> <dd> <?= h($tier->upperbound) ?> </dd>
                        <dt><?= __('Multiplier') ?>:</dt> <dd> <?= h($tier->multiplier *= 100).'%' ?> </dd>
                        <dt><?= __('Gift Coupon') ?>:</dt> <dd> <?= $tier->tier_gift_coupon ? h($tier->tier_gift_coupon['gift_coupon']['description']) : 'No Coupon Selected' ?> </dd>
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

