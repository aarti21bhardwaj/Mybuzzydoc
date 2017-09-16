<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class = "col-sm-offset-6"><?= __('Request more cards') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendorCardRequest, ['data-toggle'=>"validator",'class' => 'form-horizontal', 'enctype'=>"multipart/form-data"]) ?>
                <div class="form-group">
                    
                    <?= $this->Form->label('vendor_card_series', __('series'), ['class' => ['col-sm-2', 'control-label']]); ?>

                    <div class="col-sm-10">
                       <?= $this->Form->label('vendor_card_series', __($vendorCardSeries->series), ['class' => ['col-sm-2', 'control-label']]); ?>
                    </div>     
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                  <div class="form-group">
                    <?= $this->Form->label('count', __('Count'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('count', ['label' => false,'min' => 0, 'class' => 'form-control']); ?>
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
