<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Vendor Redeemed Points'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="vendorRedeemedPoints form large-9 medium-8 columns content">
    <?= $this->Form->create($vendorRedeemedPoint) ?>
    <fieldset>
        <legend><?= __('Add Vendor Redeemed Point') ?></legend>
        <?php
            echo $this->Form->input('vendor_id');
            echo $this->Form->input('points');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
