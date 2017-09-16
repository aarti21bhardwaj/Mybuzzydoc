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
                            <dt><?= __('Org Name') ?>:</dt> <dd><span class="label label-primary"><?= h($vendor->org_name) ?></span></dd>
                            <!-- <h5 style="float:right"><?= $this->Html->link(__('View Bill'), ['controller'=>'Vendors','action' => 'viewBill', $vendor->id] ) ?></h5> -->
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Id') ?>:</dt> <dd> <?= h($vendor->id) ?> </dd>
                        <dt><?= __('Reward Template') ?>:</dt> <dd> <?= $template ? $template->name : 'No Template' ?> </dd>
                        <dt><?= __('Minimum Deposit') ?>:</dt> <dd> <?= h($vendor->min_deposit) ?> </dd>
                         <dt><?= __('Threshold Value') ?>:</dt> <dd> <?= h($vendor->threshold_value) ?> </dd>
                         <dt><?= __('Is Legacy') ?>:</dt> <dd> <?= h($vendor->is_legacy ? 'Yes' : 'No') ?> </dd>
                        <dt><?= __('PeopleHub Id') ?>:</dt> <dd> <?= h($vendor->people_hub_identifier) ?> </dd>
                            <dt><?= __('Created') ?>:</dt> <dd> <?= h($vendor->created) ?> </dd>
                            <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendor->modified) ?></dd>
                            <dt><?= __('Status') ?>:</dt> <dd><?= h($vendor->status) ? 'Enabled' : 'Disabled' ?></dd>
                            <dt><?= __('Is Deleted') ?>:</dt> <dd><?= h($vendor->is_deleted) ? 'Yes' : 'No' ?></dd>
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
