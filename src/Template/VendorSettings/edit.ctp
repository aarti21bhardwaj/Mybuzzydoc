<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $vendorSetting->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $vendorSetting->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Vendor Settings'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vendorSettings form large-9 medium-8 columns content">
    <?= $this->Form->create($vendorSetting) ?>
    <fieldset>
        <legend><?= __('Edit Vendor Setting') ?></legend>
        <?php
            echo $this->Form->input('vendor_id', ['options' => $vendors]);
            echo $this->Form->input('setting_key_id');
            echo $this->Form->input('value');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
