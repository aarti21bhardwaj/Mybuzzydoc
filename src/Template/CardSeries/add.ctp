<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class = "col-sm-offset-6"><?= __('Request more cards') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create(null, ['data-toggle'=>"validator",'class' => 'form-horizontal', 'enctype'=>"multipart/form-data",'url' => ['controller' => 'CardSeries', 'action' => 'add']]) ?>
                <div class="form-group">

                    <?= $this->Form->label('series', __('Series'), ['class' => ['col-sm-2', 'control-label']]); ?>

                    <div class="col-sm-10">
                     <?= $this->Form->input('series', ['label' => false,'class' => ['form-control']]); ?>
                 </div>     
             </div>
             <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
             <?= $this->Form->label('vendor_id', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>

              <div class="col-sm-9">
                <?= $this->Form->input('vendor_id', ['options' => $vendors,'label' => false, 'class' => ['form-control'],'required'=>'required']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-5">
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
            </div>
        </div> 
        <?= $this->Form->end() ?>
    </div>
</div>
</div>
</div>
