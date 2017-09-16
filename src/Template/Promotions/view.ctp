<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($promotion->name) ?></h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Name') ?>:</dt> <dd> <span class="label label-primary"><?= h($promotion->vendor->org_name) ?></span> </dd>
                        <dt><?= __('Description') ?>:</dt> <dd> <?= h($promotion->description) ?> </dd>
                        <dt><?= __('Points') ?>:</dt> <dd> <?= h($promotion->points) ?> </dd>
                        <dt><?= __('Frequency') ?>:</dt> <dd> <?= h($promotion->frequency) ?> </dd>
                            <!-- <dt><?= __('Is Deleted') ?>:</dt> <dd><?= h($promotion->is_deleted) ? 'Yes' : 'No' ?></dd> -->
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
