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
                                <dt><?= __('Card Series') ?>:</dt> <dd><span class="label label-primary"><?= h($vendorCardRequest->vendor_card_series) ?></span></dd>
                                <!-- <h5 style="float:right"><?= $this->Html->link(__('View Bill'), ['controller'=>'Vendors','action' => 'viewBill', $vendor->id] ) ?></h5> -->
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('Start') ?>:</dt> <dd> <?= h($vendorCardRequest->start) ?> </dd>
                                <dt><?= __('End') ?>:</dt> <dd> <?= h($vendorCardRequest->end) ?> </dd>
                                <dt><?= __('Total Requested Cards') ?>:</dt> <dd> <?= $this->Number->format($vendorCardRequest->end - $vendorCardRequest->start) ?> </dd>
                                <dt><?= __('Is Issued') ?>:</dt> <dd> <?= h($vendorCardRequest->is_issued ? 'Yes' : 'No') ?> </dd>
                                 <?php if($loggedInUserRole == 'admin'){ ?>
                                <dt><?= __('Is rejected') ?>:</dt> <dd> <?= h($vendorCardRequest->status ? 'No' : 'Yes') ?> </dd>
                                <dt><?= __('Remarks') ?>:</dt> <dd><?= h($vendorCardRequest->remark) ?></dd>
                                 <?php } ?>
                                <dt><?= __('Created') ?>:</dt> <dd> <?= h($vendorCardRequest->created) ?> </dd>
                                <dt><?= __('Modified') ?>:</dt> <dd><?= h($vendorCardRequest->modified) ?></dd>
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
