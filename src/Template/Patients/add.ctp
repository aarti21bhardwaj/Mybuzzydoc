<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class = "col-sm-offset-6"><?= __('Upload File') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create(null, ['data-toggle'=>"validator",'class' => 'form-horizontal', 'enctype'=>"multipart/form-data",'url' => ['controller' => 'Patients', 'action' => 'add']]) ?>
                
                <div class="form-group">
                    <?= $this->Form->label('file', __('Upload File'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-4">
                        <?= $this->Form->input('file', ['accept'=>'text/csv','label' => false,'required' => true,['class' => 'form-control'],'type' => "file"]); ?>
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
