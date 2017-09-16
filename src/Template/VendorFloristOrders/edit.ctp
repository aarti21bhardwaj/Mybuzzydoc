<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $vendorFloristOrder->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $vendorFloristOrder->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Vendor Florist Orders'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Vendor Florist Transactions'), ['controller' => 'VendorFloristTransactions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor Florist Transaction'), ['controller' => 'VendorFloristTransactions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vendorFloristOrders form large-9 medium-8 columns content">
    <?= $this->Form->create($vendorFloristOrder) ?>
    <fieldset>
        <legend><?= __('Edit Vendor Florist Order') ?></legend>
        <?php
            echo $this->Form->input('vendor_id', ['options' => $vendors]);
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('product_id');
            echo $this->Form->input('status');
            echo $this->Form->input('message');
            echo $this->Form->input('delivery_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
