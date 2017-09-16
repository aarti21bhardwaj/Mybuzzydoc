<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $question->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $question->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Questions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Question Types'), ['controller' => 'QuestionTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question Type'), ['controller' => 'QuestionTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Survey Questions'), ['controller' => 'SurveyQuestions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Survey Question'), ['controller' => 'SurveyQuestions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="questions form large-9 medium-8 columns content">
    <?= $this->Form->create($question) ?>
    <fieldset>
        <legend><?= __('Edit Question') ?></legend>
        <?php
            echo $this->Form->input('qid');
            echo $this->Form->input('text');
            echo $this->Form->input('question_type_id', ['options' => $questionTypes]);
            echo $this->Form->input('frequency');
            echo $this->Form->input('points');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div> -->

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Question') ?></h5>
            </div>

            <div class="ibox-content">
              <?= $this->Form->create($question, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                        <?= $this->Form->label('text', __('Question'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('text', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>     
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                            <?= $this->Form->label('question_type_id', __('Question Type'), ['class' => ['col-sm-2', 'control-label']]) ?>
                            <div class="col-sm-10">
                                <?= $this->Form->input('question_type_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $questionTypes ]); ?>
                            </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            Frequency
                            <br>
                            <small class="text-navy">(In number of days)</small>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('frequency', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                        <div class="form-group">
                            <?= $this->Form->label('points', __('Points'), ['class' => ['col-sm-2', 'control-label']]) ?>
                            <div class="col-sm-4">
                                <?= $this->Form->input('points', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
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

