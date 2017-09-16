<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Assessment Survey') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($assessmentSurvey, [ 'data-toggle'=>"validator", 'name' => 'formEdit','class' => 'form-horizontal']) ?>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Survey Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('name', ['label' => false, 'placeholder' => 'name','required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div> 
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('type', __('Survey Type'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?= $this->Form->select('survey_type_id', $surveyTypes ,['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('Questions', __('Edit Questions'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?=$this->Html->link('Edit Questions', ['controller' => 'AssessmentSurveyQuestions', 'action' => 'index', "?" => ["assessment_survey_id" => $assessmentSurvey->id]],['class' => ['btn', 'btn-success']])?>
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