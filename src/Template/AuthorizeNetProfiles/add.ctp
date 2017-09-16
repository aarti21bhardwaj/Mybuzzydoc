<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Authorize Net Profiles'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="authorizeNetProfiles form large-9 medium-8 columns content">
    <?= $this->Form->create($authorizeNetProfile) ?>
    <fieldset>
        <legend><?= __('Add Authorize Net Profile') ?></legend>
        <?php
            echo $this->Form->input('user_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
