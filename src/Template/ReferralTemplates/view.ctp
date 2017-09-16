<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2></h2>
                        </div>
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Name') ?>:</dt> 
                            <dd>
                                <span class="label label-primary">
                                    <?= h($referralTemplate->vendor->org_name) ?>
                                </span>
                            </dd>
                        </dl>
                       
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <dl class="dl-horizontal">
                        <dt><?= __('Subject') ?>:</dt> <dd> <?= h($referralTemplate->subject) ?> </dd>
                        <dt><?= __('Description') ?>:</dt> <dd> <?= h($referralTemplate->description) ?> </dd>
                        <dt><?= __('Created') ?>:</dt> <dd><?= h($referralTemplate->created) ?></dd>
                        <dt><?= __('Modified') ?>:</dt> <dd><?= h($referralTemplate->modified) ?></dd>
                        <dt><?= __('Status') ?>:</dt> <dd><?= h($referralTemplate->status)?'Enabled':'Disabled' ?></dd>
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

