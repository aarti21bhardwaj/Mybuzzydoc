<div class = "row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md">
                                <h2><?= h($user->first_name." ".$user->last_name) ?></h2>
                            </div>
                            <dl class="dl-horizontal">
                            <dt><?= __('Practice Name') ?>:</dt> 
                            <dd>
                                <span class="label label-primary"><?= h($user->vendor->org_name) ?></span>
                            </dd>
                            </dl> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('Practice Location') ?>:</dt> <dd> <?= ($user->vendor_location_id != 0) ? h($user->vendor_location->address) : 'N/A' ?> </dd>
                                <dt><?= __('First Name') ?>:</dt> <dd> <?= h($user->first_name) ?> </dd>
                                <dt><?= __('Last Name') ?>:</dt> <dd> <?= h($user->last_name) ?> </dd>
                                <dt><?= __('Username') ?>:</dt> <dd> <?= h($user->username) ?> </dd>
                                <dt><?= __('Email') ?>:</dt> <dd> <?= h($user->email) ?> </dd>
                                <dt><?= __('Phone') ?>:</dt> <dd> <?= h($user->phone) ?> </dd>
                                <dt><?= __('Role') ?>:</dt> <dd> <?= h($user->role['label']) ?> </dd>
                                <dt><?= __('Created') ?>:</dt> <dd> <?= h($user->created) ?> </dd>
                                <dt><?= __('Modified') ?>:</dt> <dd><?= h($user->modified) ?></dd>
                                <dt><?= __('Status') ?>:</dt> <dd><?= h($user->status)?'Enabled':'Disabled' ?></dd>
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



