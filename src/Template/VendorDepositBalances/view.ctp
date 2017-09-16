<div class="row">
<div class="col-lg-9">
    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <!-- <h2><?= h($vendor->org_name) ?></h2> -->
                        </div>
                        <dl class="dl-horizontal">
                            <dt><?= __('Practice') ?>:</dt> <dd><span class="label label-primary"><?= h($vendorDepositBalance->vendor->org_name) ?></span></dd>
                            <!-- <h5 style="float:right"><?= $this->Html->link(__('View Bill'), ['controller'=>'Vendors','action' => 'viewBill', $vendor->id] ) ?></h5> -->
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <dl class="dl-horizontal">
                        <dt><?= __('Balance') ?>:</dt> <dd> <?= $this->Number->format($vendorDepositBalance->balance) ?> </dd>
                        
                        <dt><?= __('Created') ?>:</dt> <dd> <?= h($vendorDepositBalance->created) ?> </dd>
                            <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendorDepositBalance->modified) ?></dd>
                             
                         
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