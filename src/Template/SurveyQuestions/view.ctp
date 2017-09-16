<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($surveyQuestion->survey->name) ?></h2> 
                        </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Survey Name') ?>:</dt> <dd><span class="label label-primary"><?= h($surveyQuestion->survey->name) ?></span></dd>
                            <dt><?= __('Question') ?>:</dt> <dd><?= h($surveyQuestion->question->text) ?></dd>
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
