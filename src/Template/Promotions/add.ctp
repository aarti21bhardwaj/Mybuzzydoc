<?= $this->Html->script(['tours/promotion-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = 'addPromotion'><?= __('Add Promotion') ?></h5>
                <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($promotion, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" Display title for the way a patient can earn points." id = 'promotionName'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Promotion Name</label>
                    <div class="col-sm-10">
                       <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>     
                </div>

                <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>

                    <!-- <div class="form-group">
                        <?= $this->Form->label('vendor_id', __('Vendor Name'), ['class' => ['col-sm-2', 'control-label']]) ?>
                        <div class="col-sm-10">
                            <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $vendors ]); ?>
                        </div>
                    </div> -->
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('description', __('Description'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'promotionDesc']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('description', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Point value awarded to patient for the promotion." id = 'promotionPoints'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Points</label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('points', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                 <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Frequency is the number of days after which the promotion will be available on a patient’s account again after it has been recorded. Example: A 'Dental Check Up' can only be awarded twice a year; the frequency should be to 180 days." id = 'promotionFrequency'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Frequency</label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('frequency', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'savePromotion']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

