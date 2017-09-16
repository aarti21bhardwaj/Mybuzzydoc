<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Plan Feature') ?></h5>
            </div>

            <div class="ibox-content">
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Plan'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                     <?= $this->Form->input('plan_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $plans]); ?>
                 </div>
             </div>

             <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <?= $this->Form->label('Features', __('Features'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10" >
                    <?php foreach ($features as $featureId => $feature) { ?>
                        <div>    
                        <label>
                            <?= $this->Form->checkbox('feature_id[]', ['value' => $featureId, 'class' => 'checkbox-primary','multiple' => true, 'options' => $features, 'hiddenField' => false ]); ?>
                            <?= $feature ?>
                        </label>
                        </div>
                    <?php } ?>
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

