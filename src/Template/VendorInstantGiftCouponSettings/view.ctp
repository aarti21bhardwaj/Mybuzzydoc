<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Instant Gift Coupon Setting'), ['action' => 'edit', $vendorInstantGiftCouponSetting->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Instant Gift Coupon Setting'), ['action' => 'delete', $vendorInstantGiftCouponSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorInstantGiftCouponSetting->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Instant Gift Coupon Settings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Instant Gift Coupon Setting'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorInstantGiftCouponSettings view large-9 medium-8 columns content">
    <h3><?= h($vendorInstantGiftCouponSetting->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Practice') ?></th>
            <td><?= $vendorInstantGiftCouponSetting->has('vendor') ? $this->Html->link($vendorInstantGiftCouponSetting->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorInstantGiftCouponSetting->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount Spent Threshold') ?></th>
            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->amount_spent_threshold) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Points Earned Threshold') ?></th>
            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->points_earned_threshold) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Threshold Time Period') ?></th>
            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->threshold_time_period) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Redemption Expiry') ?></th>
            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->redemption_expiry) ?></td>
        </tr>
    </table>
</div>
