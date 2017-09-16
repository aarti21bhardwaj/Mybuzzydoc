<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h2><?= h($vendorEmailSetting->event['name']) ?></h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                            <dt><?= __('Practice Name') ?>:</dt> <dd> <span class="label label-primary"><?= h($vendorEmailSetting->vendor->org_name) ?></span> </dd>
                            <dt><?= __('Layout') ?>:</dt> <dd> <?= h($vendorEmailSetting->email_layout['name']) ?> </dd>
                            <dt><?= __('Template Id') ?>:</dt> <dd> <?= h($vendorEmailSetting->email_template['name']) ?> </dd>
                            <dt><?= __('Event') ?>:</dt> <dd><?= h($vendorEmailSetting->event['name']) ?></dd>
                            <dt><?= __('Subject') ?>:</dt> <dd><?= h($vendorEmailSetting->subject) ?></dd>
                            <dt><?= __('Body') ?>:</dt> <dd><?= $vendorEmailSetting->body ?></dd>
                             <dt><?= __('Status') ?>:</dt> <dd><?= ($vendorEmailSetting->status == 1) ? 'Enabled':'Disabled' ?></dd>
                        </dl>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-lg-12 text-center">
                    <?= $this->Html->link('Back',$this->request->referer(),['class' => ['btn', 'btn-warning']]);?>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>









<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vendor Email Setting'), ['action' => 'edit', $vendorEmailSetting->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vendor Email Setting'), ['action' => 'delete', $vendorEmailSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorEmailSetting->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vendor Email Settings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor Email Setting'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vendors'), ['controller' => 'Vendors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vendor'), ['controller' => 'Vendors', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Email Layouts'), ['controller' => 'EmailLayouts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Email Layout'), ['controller' => 'EmailLayouts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Email Templates'), ['controller' => 'EmailTemplates', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Email Template'), ['controller' => 'EmailTemplates', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vendorEmailSettings view large-9 medium-8 columns content">
    <h3><?= h($vendorEmailSetting->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Vendor') ?></th>
            <td><?= $vendorEmailSetting->has('vendor') ? $this->Html->link($vendorEmailSetting->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorEmailSetting->vendor->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email Layout') ?></th>
            <td><?= $vendorEmailSetting->has('email_layout') ? $this->Html->link($vendorEmailSetting->email_layout->name, ['controller' => 'EmailLayouts', 'action' => 'view', $vendorEmailSetting->email_layout->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email Template') ?></th>
            <td><?= $vendorEmailSetting->has('email_template') ? $this->Html->link($vendorEmailSetting->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $vendorEmailSetting->email_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email Event') ?></th>
            <td><?= h($vendorEmailSetting->email_event) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Subject') ?></th>
            <td><?= h($vendorEmailSetting->subject) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($vendorEmailSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($vendorEmailSetting->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($vendorEmailSetting->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Body') ?></h4>
        <?= $this->Text->autoParagraph(h($vendorEmailSetting->body)); ?>
    </div>
</div>
 -->