<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Vendor Redeemed Point'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vendorRedeemedPoints index large-9 medium-8 columns content">
    <h3><?= __('Vendor Redeemed Points') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('vendor_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('points') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendorRedeemedPoints as $vendorRedeemedPoint): ?>
            <tr>
                <td><?= $this->Number->format($vendorRedeemedPoint->id) ?></td>
                <td><?= $this->Number->format($vendorRedeemedPoint->vendor_id) ?></td>
                <td><?= $this->Number->format($vendorRedeemedPoint->points) ?></td>
                <td><?= h($vendorRedeemedPoint->created) ?></td>
                <td><?= h($vendorRedeemedPoint->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $vendorRedeemedPoint->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $vendorRedeemedPoint->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $vendorRedeemedPoint->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorRedeemedPoint->id)]) ?>
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
