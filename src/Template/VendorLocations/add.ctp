<?= $this->Html->script(['tours/location-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = 'addLoctions'><?= __('Add Practice Location') ?></h5>
                <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorLocation, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>
                <!-- <div class="form-group">
                  
                    <?= $this->Form->label('vendor', __('Vendor Id'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div> -->
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('address', __('Address'), ['class' => ['col-sm-2', 'control-label'], 'id' =>'address']); ?>
                    <?php $addressPH = " Add your practice address. This will show up on the rating & review form to be filled up by your patients." ?> 
                    <div class="col-sm-10">
                       <?= $this->Form->input('address', ['label' => false, 'placeholder' => $addressPH,'class' => 'form-control']);?>
                    </div>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('Facebook Business Page URL'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'fb-url']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('fb_url', ['label' => false, 'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('G+ Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('google_url', ['label' => false,'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('Yelp Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('yelp_url', ['label' => false,'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('RateMDs Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('ratemd_url', ['label' => false,'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('Yahoo Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('yahoo_url', ['label' => false,'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('Healthgrades Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('healthgrades_url', ['label' => false,'type' => 'url', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('HashTag'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('hash_tag', ['label' => false, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('is_default', ['label' => false, 'id' => 'defaultCheckbox']); ?> Default Location
                        </label>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-5">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveLocation']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['id' => 'sub', 'class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>