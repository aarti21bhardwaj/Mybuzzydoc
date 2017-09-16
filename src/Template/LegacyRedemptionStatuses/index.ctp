<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Legacy Redemption Status'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Legacy Redemptions'), ['controller' => 'LegacyRedemptions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Legacy Redemption'), ['controller' => 'LegacyRedemptions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="legacyRedemptionStatuses index large-9 medium-8 columns content">
    <h3><?= __('Legacy Redemption Statuses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($legacyRedemptionStatuses as $legacyRedemptionStatus): ?>
            <tr>
                <td><?= $this->Number->format($legacyRedemptionStatus->id) ?></td>
                <td><?= h($legacyRedemptionStatus->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $legacyRedemptionStatus->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $legacyRedemptionStatus->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $legacyRedemptionStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $legacyRedemptionStatus->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
