<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Referral Template') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($referralTemplate, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Select Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                      <?= $this->Form->input('vendor_id', ['options' => $vendors,'label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>
                <!-- <div class="form-group">
                  
                    <?= $this->Form->label('name', __('Select Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div> -->
                
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                     <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Subject for the referral email." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Subject</label>
                    <div class="col-sm-10">
                       <?= $this->Form->input('subject', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Sample text for the referral email." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Description</label>
                    <div class="col-sm-10">
                       <?= $this->Form->input('description', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['label' => false]); ?> Active
                        </label>
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