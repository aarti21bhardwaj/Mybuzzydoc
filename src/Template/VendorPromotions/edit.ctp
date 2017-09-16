<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
             <h5><?= __('Edit Practice Promotions') ?></h5>
            </div>
            <div class="ibox-content">
                 <?= $this->Form->create($vendorPromotion, ['data-toggle'=>"validator", 'class' => 'form-horizontal']) ?> 
                 <?php if($loggedInUser['role']->name == 'admin'){ ?>
                 <div class="form-group">
                    <?= $this->Form->label('vendor_id', __('Vendor Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                     <?= $this->Form->input('vendor_id', ['label' => false, 'class' => ['form-control'], 'options' => $vendors]); ?>
                    </div>
                 </div>
                 <?php } ?>
                 <?php if(in_array($loggedInUser['vendor_id'], [1, $promotion->vendor_id])):?>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" Display title for the way a patient can earn points." id = 'promotionName'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Promotion Name</label>
                        <div class="col-sm-10">
                           <?= $this->Form->input('promotion.name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>     
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="This is what patient will see on their Patient Portal." id = 'promotionDesc'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Description</label>
                        <div class="col-sm-10">
                           <?= $this->Form->input('promotion.description', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!in_array($loggedInUser['vendor_id'], [1, $promotion->vendor_id])):?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" Display title for the way a patient can earn points." id = 'promotionName'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Promotion Name</label>
                        <div class="col-sm-10">
                           <?= $promotion->name ?>
                        </div>     
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="This is what patient will see on their Patient Portal." id = 'promotionDesc'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Description</label>
                        <div class="col-sm-10">
                           <?= $promotion->description ?>
                        </div>
                    </div>
                <?php endif; ?>

                 <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class= "control-label">
                   <label class = "col-sm-2" data-toggle="tooltip" data-placement="top" title=" Point value awarded to patient for the promotion." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Points</label>
                  </div>
                    <div class="col-sm-4">
                        <?= $this->Form->input('points', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                    <div class= "control-label">
                        <label class = "col-sm-2" data-toggle="tooltip" data-placement="top" title="Frequency is the number of days after which the promotion will be available on a patient’s account again after it has been recorded. Example: A 'Dental Check Up' can only be awarded twice a year; the frequency should be to 180 days." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Frequency</label>
                    </div>
                        <div class="col-sm-4">
                            <?= $this->Form->input('frequency', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                 <?= $this->Inspinia->horizontalRule(); ?>
                 <div class="form-group">
                  <?= $this->Form->label('patient_portal_status', __('Patient Portal Status'), ['class' => ['col-sm-2', 'control-label']]); ?>
                  <div class="col-sm-10">
                  <input style="display: none;" type="checkbox">
                    <?= $this->Form->input('patient_portal_status', ['label' => false,'type'=>'checkbox', 'class' => ['form-control', 'js-switch']]); ?>
                  </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                 <div class="form-group">
                  <?= $this->Form->label('is_note_required', __('Is Note Required ?'), ['class' => ['col-sm-2', 'control-label']]); ?>
                  <div class="col-sm-10">
                  <input style="display: none;" type="checkbox">
                    <?= $this->Form->input('is_note_required', ['label' => false,'type'=>'checkbox', 'class' => ['form-control', 'js-switch-2']]); ?>
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

<script type="text/javascript">
    
    $(document).ready(function(){
        
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
        var elem2 = document.querySelector('.js-switch-2');
        var switchery = new Switchery(elem2, { color: '#1AB394' });
    });

</script>