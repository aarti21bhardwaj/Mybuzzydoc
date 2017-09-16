<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Flowers') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorFloristSetting, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <?= $this->Form->label('vendor_id', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                 <?= $this->Form->input('vendor_id', ['empty' => '---Select---','label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $vendors]); ?>
                     </div>
                 </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                    <?= $this->Form->label('image', __('Image'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-4">
                        <div class="img-thumbnail">
                            <?= $this->Html->image('$vendorFloristSettings->image_url', array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
                        </div>
                        <br> </br>
                        <?= $this->Form->input('image_name', ['accept'=>"image/*",'label' => false,'required' => true,['class' => 'form-control'],'type' => "file",'id'=>'imgChange']); ?>
                    </div> 
                </div>
               <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <?= $this->Form->label('message', __('Message'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                <?= $this->Form->input('message', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                     </div>
                 </div>
                 <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('address', __('Address'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('address', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('city', __('City'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('city', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('state', __('State'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('state', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('zip', __('Zip code'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('zip', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('country', __('Country'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('country', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
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
 

