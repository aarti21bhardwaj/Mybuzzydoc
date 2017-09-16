<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Redeemed Point'), ['action' => 'edit', $vendorRedeemedPoint->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Redeemed Point'), ['action' => 'delete', $vendorRedeemedPoint->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorRedeemedPoint->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Redeemed Points'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Redeemed Point'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorRedeemedPoints view large-9 medium-8 columns content">
    <h3><?= h($vendorRedeemedPoint->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorRedeemedPoint->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Vendor Id') ?></th>
            <td><?= $this->Number->format($vendorRedeemedPoint->vendor_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Points') ?></th>
            <td><?= $this->Number->format($vendorRedeemedPoint->points) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorRedeemedPoint->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorRedeemedPoint->modified) ?></td>
        </tr>
    </table>
</div>
