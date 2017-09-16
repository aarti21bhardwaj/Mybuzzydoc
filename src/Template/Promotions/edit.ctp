<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Promotion') ?></h5>
            </div>

            <div class="ibox-content">
                <?= $this->Form->create($promotion, ['data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                 
                    <?= $this->Form->label('name', __('Promotion Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                     <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                 </div>
             </div> 

             <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Vendor Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>


             <!-- <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <?= $this->Form->label('vendor_id', __('Vendor Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                 <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $vendors]); ?>
                </div>
            </div> -->
             <?= $this->Inspinia->horizontalRule(); ?>
             <div class="form-group">
                <?= $this->Form->label('description', __('Description'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                 <?= $this->Form->input('description', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                </div>
            </div>
            <?= $this->Inspinia->horizontalRule(); ?>
            <div class="form-group">
                <?= $this->Form->label('points', __('Points'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-4">
                    <?= $this->Form->input('points', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                </div>
            </div>
             <?= $this->Inspinia->horizontalRule(); ?>
            <div class="form-group">
                <?= $this->Form->label('frequency', __('Frequency'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-4">
                    <?= $this->Form->input('frequency', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
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
