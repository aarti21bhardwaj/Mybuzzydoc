<?= $this->Html->script(['tours/surveyQuestion-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Practice Survey Question') ?></h5>
                 <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
           <div class="ibox-content">
                <?= $this->Form->create($vendorSurveyQuestion, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal','enctype'=>"multipart/form-data"]) ?>
                <div class="form-group">

                    <?= $this->Form->label('vendor_survey', __('Practice Survey'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_survey_id', ['options' => $vendorSurveys, 'label' => false, 'class' => ['form-control'], 'disabled' => true]); ?>
                    </div>
                </div>
                <br><br>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Displayed on patient’s account." id='surveyquestion'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Survey Question</label>
                    <div class="col-sm-10">
                       <?= $this->Form->input('survey_question_id', ['options' => $surveyQuestions, 'label' => false, 'class' => ['form-control'], 'disabled' => true]); ?>
                    </div>
                </div>
                <br><br>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" Points awarded if the patient complies positively with survey question." id = 'surveyPoints'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Points</label>   
                    <div class="col-sm-10">
                      <?= $this->Form->input('points', ['id'=>'input-points','onkeypress'=>"disAllowDotInIntegerInput(event);",'label' => false,'step'=>1, 'min'=>0, "type"=>"number",'onpaste'=>"return false;",['class' => 'form-control']]); ?>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveQues']) ?>  
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>      
                    </div>
                </div>            
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
