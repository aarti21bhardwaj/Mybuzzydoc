<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $referral->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $referral->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Referrals'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Referral Leads'), ['controller' => 'ReferralLeads', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Referral Lead'), ['controller' => 'ReferralLeads', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="referrals form large-9 medium-8 columns content">
    <?= $this->Form->create($referral) ?>
    <fieldset>
        <legend><?= __('Edit Referral') ?></legend>
        <?php
            echo $this->Form->input('vendor_id', ['options' => $vendors]);
            echo $this->Form->input('refer_from');
            echo $this->Form->input('refer_to');
            echo $this->Form->input('status');
            echo $this->Form->input('is_deleted', ['empty' => true]);
            echo $this->Form->input('name');
            echo $this->Form->input('phone');
            echo $this->Form->input('peoplehub_identifier');
            echo $this->Form->input('uuid');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
