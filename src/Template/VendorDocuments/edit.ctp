<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Document') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorDocument, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <?php if(isset($vendors)): ?>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('description', __('Description'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('description', ['label' => false, 'class' => 'form-control']); ?>
                        </div>
                    </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('filename', __('Filename'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('filename', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
               <div class="form-group">
                    <?= $this->Form->label('file_path', __('FilePath'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('file_path', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>