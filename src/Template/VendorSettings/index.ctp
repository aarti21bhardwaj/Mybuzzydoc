<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Vendor Setting'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vendorSettings index large-9 medium-8 columns content">
    <h3><?= __('Vendor Settings') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('vendor_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('setting_key_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendorSettings as $vendorSetting): ?>
            <tr>
                <td><?= $this->Number->format($vendorSetting->id) ?></td>
                <td><?= $vendorSetting->has('vendor') ? $this->Html->link($vendorSetting->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorSetting->vendor->id]) : '' ?></td>
                <td><?= $this->Number->format($vendorSetting->setting_key_id) ?></td>
                <td><?= h($vendorSetting->value) ?></td>
                <td><?= h($vendorSetting->created) ?></td>
                <td><?= h($vendorSetting->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $vendorSetting->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $vendorSetting->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $vendorSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorSetting->id)]) ?>
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
