<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $creditCardCharge->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $creditCardCharge->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Credit Card Charges'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="creditCardCharges form large-9 medium-8 columns content">
    <?= $this->Form->create($creditCardCharge) ?>
    <fieldset>
        <legend><?= __('Edit Credit Card Charge') ?></legend>
        <?php
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('vendor_id', ['options' => $vendors]);
            echo $this->Form->input('auth_code');
            echo $this->Form->input('transactionid');
            echo $this->Form->input('description');
            echo $this->Form->input('response_code');
            echo $this->Form->input('reason');
            echo $this->Form->input('amount');
            echo $this->Form->input('transaction_fee');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
