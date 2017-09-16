<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Assessment Surveys') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorAssessmentSurvey, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('vendor_id', __('Client'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?= $this->Form->select('vendor_id', $vendors ,['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div> 
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('assessment_survey_id', __('Survey'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                         <?= $this->Form->select('assessment_survey_id', $assessmentSurveys ,['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',['action' => 'index'],['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
