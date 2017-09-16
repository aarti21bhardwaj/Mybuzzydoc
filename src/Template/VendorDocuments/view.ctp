<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Document'), ['action' => 'edit', $vendorDocument->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Document'), ['action' => 'delete', $vendorDocument->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorDocument->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Documents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Document'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorDocuments view large-9 medium-8 columns content">
    <h3><?= h($vendorDocument->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice') ?></th>
            <td><?= $vendorDocument->has('vendor') ? $this->Html->link($vendorDocument->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorDocument->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($vendorDocument->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Filename') ?></th>
            <td><?= h($vendorDocument->filename) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorDocument->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorDocument->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorDocument->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('File Path') ?></h4>
        <?= $this->Text->autoParagraph(h($vendorDocument->file_path)); ?>
    </div>
</div>
