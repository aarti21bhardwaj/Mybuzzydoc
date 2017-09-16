<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($emailSetting->event['name']) ?></h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Layout') ?>:</dt> <dd> <?= h($emailSetting->email_layout['name']) ?> </dd>
                            <dt><?= __('Template') ?>:</dt> <dd> <?= h($emailSetting->email_template['name']) ?> </dd>
                            <dt><?= __('Event Name') ?>:</dt> <dd><?= h($emailSetting->event['name']) ?></dd>
                            <dt><?= __('Subject') ?>:</dt> <dd><?= h($emailSetting->subject) ?></dd>
                            <dt><?= __('Body') ?>:</dt> <dd><?= $emailSetting->body ?></dd>
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