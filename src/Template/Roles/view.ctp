<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h3 align="center"><?= h($role->name) ?></h3>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <dl class="dl-horizontal">
                                <dt><?= __('Id') ?>:</dt> <dd> <?= h($role->id) ?> </dd>
                                <dt><?= __('Name') ?>:</dt> <dd> <?= h($role->name) ?> </dd>
                                <dt><?= __('Label') ?>:</dt> <dd> <?= h($role->label) ?> </dd>
                                <dt><?= __('Status') ?>:</dt> <dd><?= h($role->status) ? 'Yes' : 'No' ?></dd>
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
</div>
