<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Vendor Survey Questions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Vendor Surveys'), ['controller' => 'VendorSurveys', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor Survey'), ['controller' => 'VendorSurveys', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Survey Questions'), ['controller' => 'SurveyQuestions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Survey Question'), ['controller' => 'SurveyQuestions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Survey Instance Responses'), ['controller' => 'SurveyInstanceResponses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Survey Instance Response'), ['controller' => 'SurveyInstanceResponses', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vendorSurveyQuestions form large-9 medium-8 columns content">
    <?= $this->Form->create($vendorSurveyQuestion) ?>
    <fieldset>
        <legend><?= __('Add Vendor Survey Question') ?></legend>
        <?php
            echo $this->Form->input('vendor_survey_id', ['options' => $vendorSurveys]);
            echo $this->Form->input('survey_question_id', ['options' => $surveyQuestions]);
            echo $this->Form->input('points');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
