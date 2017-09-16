<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Authorize Net Profile'), ['action' => 'edit', $authorizeNetProfile->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Authorize Net Profile'), ['action' => 'delete', $authorizeNetProfile->id], ['confirm' => __('Are you sure you want to delete # {0}?', $authorizeNetProfile->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Authorize Net Profiles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Authorize Net Profile'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="authorizeNetProfiles view large-9 medium-8 columns content">
    <h3><?= h($authorizeNetProfile->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($authorizeNetProfile->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Id') ?></th>
            <td><?= $this->Number->format($authorizeNetProfile->user_id) ?></td>
        </tr>
    </table>
</div>
