<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Survey Question') ?></h5>
            </div>

            <div class="ibox-content">
              <?= $this->Form->create($surveyQuestion, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>

                     <?= $this->Inspinia->horizontalRule();?>
                    <div class="form-group">
                            <?= $this->Form->label('survey_id', __('Survey Name'), ['class' => ['col-sm-2', 'control-label']]) ?>
                            <div class="col-sm-10">
                                <?= $this->Form->select('survey_id', $surveys ,['empty' => 'Select a Survey','label' => false,'required' => true, 'class' => 'form-control']); ?>
                            </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                            <?= $this->Form->label('question_id', __('Questions'), ['class' => ['col-sm-2', 'control-label']]) ?>
                            <div class="col-sm-10">
                                <?= $this->Form->input('question_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'empty' => 'Select a Question', 'options' => $questions]); ?>
                            </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                            <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                            <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                            </div>
                        </div> 
                    <?= $this->Form->end() ?>
             </div>
        </div>
</div>
</div>
