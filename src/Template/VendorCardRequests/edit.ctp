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
                       <?= $this->Form->label($vendorCardRequest->vendor_card_series, null, ['class' => ['col-sm-2', 'control-label']]); ?>
                    </div>      
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                  <div class="form-group">
                    <?= $this->Form->label('Count', __('Count'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->label($vendorCardRequest->count,null, ['class' => ['col-sm-2', 'control-label'],'id' => 'get_count' ]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                  <div class="form-group">
                    <?= $this->Form->label('start', __('Start'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('start', ['label' => false,'min' => 0, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Form->hidden('end_value', ['id'=>'end_value','name'=>'end','value'=>$vendorCardRequest->end]) ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('end', __('End'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                     <?= $this->Form->label($vendorCardRequest->end,null, ['class' => ['col-sm-2', 'control-label'],'id' => 'end']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>'submit']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(':input[type="submit"]').prop('disabled', true);  
    $("#start").keyup(function(){
        var endNode = parseInt($('#get_count').text()) + parseInt($("#start").val());
        $('#end').html(endNode);
        if(isNaN(endNode)){
            $(':input[type="submit"]').attr("disabled", true);
        }else{
            $('#end_value').val(endNode);
            $(':input[type="submit"]').prop('disabled', false);
        }
    });

});    
</script>

