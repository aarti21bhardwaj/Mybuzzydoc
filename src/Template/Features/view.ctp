<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Feature'), ['action' => 'edit', $feature->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Feature'), ['action' => 'delete', $feature->id], ['confirm' => __('Are you sure you want to delete # {0}?', $feature->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Features'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Feature'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Plan Features'), ['controller' => 'PlanFeatures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Plan Feature'), ['controller' => 'PlanFeatures', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="features view large-9 medium-8 columns content">
    <h3><?= h($feature->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($feature->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($feature->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Plan Features') ?></h4>
        <?php if (!empty($feature->plan_features)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Plan Id') ?></th>
                <th scope="col"><?= __('Feature Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($feature->plan_features as $planFeatures): ?>
            <tr>
                <td><?= h($planFeatures->id) ?></td>
                <td><?= h($planFeatures->plan_id) ?></td>
                <td><?= h($planFeatures->feature_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PlanFeatures', 'action' => 'view', $planFeatures->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PlanFeatures', 'action' => 'edit', $planFeatures->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PlanFeatures', 'action' => 'delete', $planFeatures->id], ['confirm' => __('Are you sure you want to delete # {0}?', $planFeatures->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
