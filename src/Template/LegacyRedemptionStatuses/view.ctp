<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Legacy Redemption Status'), ['action' => 'edit', $legacyRedemptionStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Legacy Redemption Status'), ['action' => 'delete', $legacyRedemptionStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $legacyRedemptionStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Legacy Redemption Statuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Legacy Redemption Status'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Legacy Redemptions'), ['controller' => 'LegacyRedemptions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Legacy Redemption'), ['controller' => 'LegacyRedemptions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="legacyRedemptionStatuses view large-9 medium-8 columns content">
    <h3><?= h($legacyRedemptionStatus->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($legacyRedemptionStatus->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($legacyRedemptionStatus->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Legacy Redemptions') ?></h4>
        <?php if (!empty($legacyRedemptionStatus->legacy_redemptions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Legacy Reward Id') ?></th>
                <th scope="col"><?= __('Vendor Id') ?></th>
                <th scope="col"><?= __('Legacy Redemption Status Id') ?></th>
                <th scope="col"><?= __('Transaction Number') ?></th>
                <th scope="col"><?= __('Redeemer Name') ?></th>
                <th scope="col"><?= __('Redeemer Peoplehub Identifier') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($legacyRedemptionStatus->legacy_redemptions as $legacyRedemptions): ?>
            <tr>
                <td><?= h($legacyRedemptions->id) ?></td>
                <td><?= h($legacyRedemptions->legacy_reward_id) ?></td>
                <td><?= h($legacyRedemptions->vendor_id) ?></td>
                <td><?= h($legacyRedemptions->legacy_redemption_status_id) ?></td>
                <td><?= h($legacyRedemptions->transaction_number) ?></td>
                <td><?= h($legacyRedemptions->redeemer_name) ?></td>
                <td><?= h($legacyRedemptions->redeemer_peoplehub_identifier) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'LegacyRedemptions', 'action' => 'view', $legacyRedemptions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'LegacyRedemptions', 'action' => 'edit', $legacyRedemptions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'LegacyRedemptions', 'action' => 'delete', $legacyRedemptions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $legacyRedemptions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
