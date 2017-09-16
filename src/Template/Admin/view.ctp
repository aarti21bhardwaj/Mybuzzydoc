<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('Dashboard'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Bills'), ['action' => 'viewBills']) ?></li>
        <li><?= $this->Html->link(__('Activities'), ['action' => 'viewActivity']) ?></li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($vendor->id) ?></h3>
</div>