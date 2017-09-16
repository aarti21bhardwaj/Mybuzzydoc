<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                           <h2><?= h($vendorSurvey->survey->name) ?></h2> 
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Practice Name') ?>:</dt> <dd> <span class="label label-primary"><?= h($vendorSurvey->vendor->org_name) ?></span> </dd>
                        <dt><?= __('Survey Name') ?>:</dt> <dd><?= h($vendorSurvey->survey->name) ?></dd>
                        <dt><?= __('Created') ?>:</dt> <dd><?= h($vendorSurvey->created) ?></dd>
                        <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendorSurvey->modified) ?></dd>
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