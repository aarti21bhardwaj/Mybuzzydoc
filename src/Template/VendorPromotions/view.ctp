<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Promotion'), ['action' => 'edit', $vendorPromotion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Promotion'), ['action' => 'delete', $vendorPromotion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorPromotion->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Promotions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Promotion'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Promotions'), ['controller' => 'Promotions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Promotion'), ['controller' => 'Promotions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorPromotions view large-9 medium-8 columns content">
    <h3><?= h($vendorPromotion->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice') ?></th>
            <td><?= $vendorPromotion->has('vendor') ? $this->Html->link($vendorPromotion->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorPromotion->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Promotion') ?></th>
            <td><?= $vendorPromotion->has('promotion') ? $this->Html->link($vendorPromotion->promotion->name, ['controller' => 'Promotions', 'action' => 'view', $vendorPromotion->promotion->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorPromotion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Points') ?></th>
            <td><?= $this->Number->format($vendorPromotion->points) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Published') ?></th>
            <td><?= $vendorPromotion->published ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
