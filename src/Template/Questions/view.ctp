<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <!-- <h2><?= h($question->text) ?></h2> -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Question') ?>:</dt> <dd><span class="label label-primary"><?= h($question->text) ?></span></dd>
                        <dt><?= __('Question Type') ?>:</dt> <dd><?= h($question->question_type->name) ?></dd>
                        <dt><?= __('Frequency') ?>:</dt> <dd> <?= $this->Number->format($question->frequency) ?></dd>
                        <dt><?= __('Points') ?>:</dt> <dd> <?= $this->Number->format($question->points) ?></dd>
                        <dt><?= __('Created') ?>:</dt> <dd><?= h($question->created) ?></dd>
                        <dt><?= __('Modified') ?>:</dt> <dd><?= h($question->modified) ?></dd>
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