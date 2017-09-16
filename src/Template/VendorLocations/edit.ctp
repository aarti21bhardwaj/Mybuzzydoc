<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Practice Location') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorLocation, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('address', __('Address'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <?php $addressPH = " Add your practice address. This will show up on the rating & review form to be filled up by your patients." ?> 
                    <div class="col-sm-10">
                       <?= $this->Form->input('address', ['label' => false, 'placeholder' => $addressPH,'class' => ['form-control']]);?>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('url', __('Facebook Business Page URL'), ['class' => ['col-sm-2', 'control-label']]); ?>
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
                            <?= $this->Form->checkbox('is_default', ['label' => false]); ?> Default Location
                        </label>
                    </div>
                </div>
               
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['id' => 'sub', 'class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['id' => 'canc', 'class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

