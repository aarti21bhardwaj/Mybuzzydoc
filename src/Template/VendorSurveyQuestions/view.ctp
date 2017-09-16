<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Survey Question'), ['action' => 'edit', $vendorSurveyQuestion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Survey Question'), ['action' => 'delete', $vendorSurveyQuestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorSurveyQuestion->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Survey Questions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Survey Question'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Surveys'), ['controller' => 'VendorSurveys', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Survey'), ['controller' => 'VendorSurveys', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Survey Questions'), ['controller' => 'SurveyQuestions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Survey Question'), ['controller' => 'SurveyQuestions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Survey Instance Responses'), ['controller' => 'SurveyInstanceResponses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Survey Instance Response'), ['controller' => 'SurveyInstanceResponses', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorSurveyQuestions view large-9 medium-8 columns content">
    <h3><?= h($vendorSurveyQuestion->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice Survey') ?></th>
            <td><?= $vendorSurveyQuestion->has('vendor_survey') ? $this->Html->link($vendorSurveyQuestion->vendor_survey->name, ['controller' => 'VendorSurveys', 'action' => 'view', $vendorSurveyQuestion->vendor_survey->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Survey Question') ?></th>
            <td><?= $vendorSurveyQuestion->has('survey_question') ? $this->Html->link($vendorSurveyQuestion->survey_question->id, ['controller' => 'SurveyQuestions', 'action' => 'view', $vendorSurveyQuestion->survey_question->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorSurveyQuestion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Points') ?></th>
            <td><?= $this->Number->format($vendorSurveyQuestion->points) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Survey Instance Responses') ?></h4>
        <?php if (!empty($vendorSurveyQuestion->survey_instance_responses)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Practice Survey Instance Id') ?></th>
                <th scope="col"><?= __('Practice Survey Question Id') ?></th>
                <th scope="col"><?= __('Response') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($vendorSurveyQuestion->survey_instance_responses as $surveyInstanceResponses): ?>
            <tr>
                <td><?= h($surveyInstanceResponses->id) ?></td>
                <td><?= h($surveyInstanceResponses->vendor_survey_instance_id) ?></td>
                <td><?= h($surveyInstanceResponses->vendor_survey_question_id) ?></td>
                <td><?= h($surveyInstanceResponses->response) ?></td>
                <td><?= h($surveyInstanceResponses->created) ?></td>
                <td><?= h($surveyInstanceResponses->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SurveyInstanceResponses', 'action' => 'view', $surveyInstanceResponses->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SurveyInstanceResponses', 'action' => 'edit', $surveyInstanceResponses->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SurveyInstanceResponses', 'action' => 'delete', $surveyInstanceResponses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $surveyInstanceResponses->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
