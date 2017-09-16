<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Florist Setting'), ['action' => 'edit', $vendorFloristSetting->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Florist Setting'), ['action' => 'delete', $vendorFloristSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorFloristSetting->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Florist Settings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Florist Setting'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorFloristSettings view large-9 medium-8 columns content">
    <h3><?= h($vendorFloristSetting->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice') ?></th>
            <td><?= $vendorFloristSetting->has('vendor') ? $this->Html->link($vendorFloristSetting->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorFloristSetting->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorFloristSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Id') ?></th>
            <td><?= $this->Number->format($vendorFloristSetting->product_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorFloristSetting->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorFloristSetting->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Message') ?></h4>
        <?= $this->Text->autoParagraph(h($vendorFloristSetting->message)); ?>
    </div>
</div>
