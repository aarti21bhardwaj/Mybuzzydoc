<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Florist Order'), ['action' => 'edit', $vendorFloristOrder->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Florist Order'), ['action' => 'delete', $vendorFloristOrder->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorFloristOrder->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Florist Orders'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Florist Order'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Florist Transactions'), ['controller' => 'VendorFloristTransactions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Florist Transaction'), ['controller' => 'VendorFloristTransactions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorFloristOrders view large-9 medium-8 columns content">
    <h3><?= h($vendorFloristOrder->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice') ?></th>
            <td><?= $vendorFloristOrder->has('vendor') ? $this->Html->link($vendorFloristOrder->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorFloristOrder->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $vendorFloristOrder->has('user') ? $this->Html->link($vendorFloristOrder->user->name, ['controller' => 'Users', 'action' => 'view', $vendorFloristOrder->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorFloristOrder->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Id') ?></th>
            <td><?= $this->Number->format($vendorFloristOrder->product_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delivery Date') ?></th>
            <td><?= h($vendorFloristOrder->delivery_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorFloristOrder->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorFloristOrder->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $vendorFloristOrder->status ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Message') ?></h4>
        <?= $this->Text->autoParagraph(h($vendorFloristOrder->message)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Vendor Florist Transactions') ?></h4>
        <?php if (!empty($vendorFloristOrder->vendor_florist_transactions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Vendor Florist Order Id') ?></th>
                <th scope="col"><?= __('Response Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($vendorFloristOrder->vendor_florist_transactions as $vendorFloristTransactions): ?>
            <tr>
                <td><?= h($vendorFloristTransactions->id) ?></td>
                <td><?= h($vendorFloristTransactions->vendor_florist_order_id) ?></td>
                <td><?= h($vendorFloristTransactions->response_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'VendorFloristTransactions', 'action' => 'view', $vendorFloristTransactions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'VendorFloristTransactions', 'action' => 'edit', $vendorFloristTransactions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'VendorFloristTransactions', 'action' => 'delete', $vendorFloristTransactions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorFloristTransactions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
