<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Assessment Survey Question') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($assessmentSurveyQuestion, ['data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                        <div class="form-group">

                            <?= $this->Form->label('name', __('Question Text'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                            <?= $this->Form->input('text', ['label' => false, 'placeholder' => 'Question Text','required' => true, 'type'=> 'text','class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('type', __('Response Group'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                                <?= $this->Form->select('response_group_id', $responseGroups ,['empty' => '--Please Select--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <?= $this->Form->button(__('Save'), ['class' => ['btn', 'btn-primary']]) ?>
                            </div>
                        </div>
                    <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>