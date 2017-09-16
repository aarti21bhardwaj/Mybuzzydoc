<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Document') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorDocument, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal', 'type' => 'file', 'enctype'=>"multipart/form-data"]) ?>
                <?php if($vendors): ?>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('vendor_name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="The document name you add here will show up on the patient portal for your patients to view." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Name</label>
                        <div class="col-sm-10">
                           <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                        </div>
                    </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Add description of your document here." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Description</label>
                        <div class="col-sm-10">
                           <?= $this->Form->input('description', ['label' => false, 'class' => 'form-control']); ?>
                        </div>
                    </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="The file extensions that can be added here are PDF, DOCX, JPG, PNG and JPEG." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>File</label>
                    <div class="col-sm-4">
                        <?= $this->Form->input('filename', ['accept'=>".jpg,.jpeg,.png,.doc,.docx,.pdf",'label' => false,'required' => true,['class' => 'form-control'],'type' => "file",'id'=>'fileChange']); ?>
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