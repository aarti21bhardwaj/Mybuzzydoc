<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $legacyRedemptionStatus->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $legacyRedemptionStatus->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Legacy Redemption Statuses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Legacy Redemptions'), ['controller' => 'LegacyRedemptions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Legacy Redemption'), ['controller' => 'LegacyRedemptions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="legacyRedemptionStatuses form large-9 medium-8 columns content">
    <?= $this->Form->create($legacyRedemptionStatus) ?>
    <fieldset>
        <legend><?= __('Edit Legacy Redemption Status') ?></legend>
        <?php
            echo $this->Form->input('name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
