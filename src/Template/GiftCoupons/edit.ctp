<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Gift Coupon') ?></h5>
            </div>

            <div class="ibox-content">
                <?= $this->Form->create($giftCoupon, ['data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <?php if(isset($vendors)){ ?>
            <div class="form-group">
                <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                 <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
             </div>
         </div>
         <?php } ?>
                <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Gift coupon details." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Description</label>
                <div class="col-sm-10">
                 <?= $this->Form->input('description', ['label' => false, 'maxlength' => 100, 'required' => true, 'class' => ['form-control']]); ?>
                </div>
            </div>
            <?= $this->Inspinia->horizontalRule(); ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" The amount of points required to redeem for a gift coupon." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Points</label>
                <div class="col-sm-4">
                    <?= $this->Form->input('points', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                </div>
            </div>
            <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Time (in days) until the issued gift coupon expires." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>
                            Expiry Duration
                            <br>
                            <small class="text-navy">(In number of days)</small>
                        </label>
                <div class="col-sm-4">
                    <?= $this->Form->input('expiry_duration', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>1,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                </div>
            </div>

            <?= $this->Inspinia->horizontalRule(); ?>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                    <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                </div>
            </div> 
            <?= $this->Form->end() ?>
         </div>
       </div>
    </div>
</div>  
