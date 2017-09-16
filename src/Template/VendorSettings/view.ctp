<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Setting'), ['action' => 'edit', $vendorSetting->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Setting'), ['action' => 'delete', $vendorSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorSetting->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Settings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Setting'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorSettings view large-9 medium-8 columns content">
    <h3><?= h($vendorSetting->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Vendor') ?></th>
            <td><?= $vendorSetting->has('vendor') ? $this->Html->link($vendorSetting->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorSetting->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value') ?></th>
            <td><?= h($vendorSetting->value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Setting Key Id') ?></th>
            <td><?= $this->Number->format($vendorSetting->setting_key_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorSetting->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorSetting->modified) ?></td>
        </tr>
    </table>
</div>
