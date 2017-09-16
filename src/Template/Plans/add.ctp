<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Plans') ?></h5>
            </div>

            <div class="ibox-content">
                <?= $this->Form->create($plan, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal','enctype'=>"multipart/form-data"]) ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Plan'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                     <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
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
