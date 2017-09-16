<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Training Video') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($trainingVideo, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('title', __('Video Title'), ['class' => ['col-sm-3', 'control-label']]); ?>
                        <div class="col-sm-9">
                           <?= $this->Form->input('title', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('embedded_source', __('Video Embedded Source'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                       <?= $this->Form->input('embedded_source', ['type' => 'textarea','label' => false,'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-3">
                        <?= $this->Form->button(__('Save Training Video'), ['class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
