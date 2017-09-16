<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
             <h5><?= __('Edit Reward') ?></h5>
         </div>

         <div class="ibox-content">
             <?= $this->Form->create($legacyReward, ['id'=>'edit-reward-form','data-toggle'=>"validator", 'class' => 'form-horizontal','enctype'=>"multipart/form-data"]) ?>
             <div class="form-group">
                 <div class= "control-label">
                   <label class = "col-sm-2" data-toggle="tooltip" data-placement="top" title="Product or Service Name (visible on dashboard)." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Reward Name</label>
                  </div>
                <div class="col-sm-10">
                 <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
             </div>
         </div> 
         <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
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
            <?= $this->Form->label('vendor_id', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
            <div class="col-sm-10">
             <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true,'class' => ['form-control'], 'options' => $vendors]); ?>
         </div>
     </div> -->

     <?= $this->Inspinia->horizontalRule(); ?>
     <div class="form-group">
        <?= $this->Form->label('reward_category_id', __('Reward Category'), ['class' => ['col-sm-2', 'control-label']]); ?>
        <div class="col-sm-10">
         <?= $this->Form->input('reward_category_id', ['label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $rewardCategories]); ?>
     </div>
 </div>

 
 <?= $this->Inspinia->horizontalRule(); ?>
     <div class="form-group">
        <?= $this->Form->label('product_type_id', __('Product Type'), ['class' => ['col-sm-2', 'control-label']]); ?>
        <div class="col-sm-10">
         <?= $this->Form->input('product_type_id', ['id'=> 'product-type-id','label' => false, 'required' => true, 'class' => ['form-control'], 'options' => $productTypes]); ?>
     </div>
 </div>

<?php
  if (!($legacyReward->points))
    {
        $hidden_points='hidden';
        $hidden_amount='';

    } 
    else
    {
        $hidden_points='';
        $hidden_amount='hidden';

    }   
    ?>

<div <?= $hidden_points ?> id='points_div'>
 <?= $this->Inspinia->horizontalRule(); ?>
 <div class="form-group">
    <div class= "control-label">
                   <label class = "col-sm-2" data-toggle="tooltip" data-placement="top" title="The amount of points each item costs the patient to redeem (50pts = $1). Points are set only for in house product type." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Points</label>
                  </div>
    <div class="col-sm-10">
      <?= $this->Form->input('points', ['id'=>'input-points','onkeypress'=>"disAllowDotInIntegerInput(event);",'label' => false,'step'=>1, 'min'=>0, "type"=>"number",'onpaste'=>"return false;",['class' => 'form-control']]); ?>
  </div>
</div>
</div>
<div <?= $hidden_amount ?> id='amount_div'>
<?= $this->Inspinia->horizontalRule(); ?>
 <div class="form-group">
     <div class= "control-label">
                   <label class = "col-sm-2" data-toggle="tooltip" data-placement="top" title="E-gift card (Amazon/Tango) redemption amount." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Amount</label>
                  </div>
    <div class="col-sm-10">
      <?= $this->Form->input('amount', ['id'=>'input-amount','onkeypress'=>"disAllowDotInIntegerInput(event);",'label' => false,'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", ['class' => 'form-control']]);
      ?>
  </div>
</div>
</div>

<?= $this->Inspinia->horizontalRule(); ?>
<div class="form-group">
    <?= $this->Form->label('image', __('Image'), ['class' => ['col-sm-2', 'control-label']]); ?>
    <div class="col-sm-4">
        <div class="img-thumbnail">
            <?= $this->Html->image($legacyReward->image_url, array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
        </div>
        <br> </br>
        <?= $this->Form->input('image_name', ['accept'=>"image/*",'label' => false,['class' => 'form-control'],'type' => "file",'id'=>'imgChange', 'required' => true] ); ?>
    </div> 
</div>

<?php if($loggedInUser['role_id'] == 1){?>

<?= $this->Inspinia->horizontalRule(); ?>
<div class="form-group">
    <?= $this->Form->label('status', __('Status'), ['class' => ['col-sm-2', 'control-label']]); ?>
    <div class="col-sm-4">
        <?= $this->Form->checkbox('status', ['type'=>'checkbox','label' => false, 'class' => ['form-control']]); ?>
    </div>
</div>

<?php } ?>

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


<script type ="text/style">

    .img-thumbnail {
    background: #fff none repeat scroll 0 0;
    height: 200px;
    margin: 10px 5px;
    padding: 0;
    position: relative;
    width: 200px;
}
.img-thumbnail img {
border: 1px solid #dcdcdc;
max-width: 100%;
object-fit: cover;
}
</script>

<!-- /*
     * @method uploadImage
       @return null
       */  -->
       <script type ="text/javascript">

          function uploadImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#upload-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

    $(document).ready(function(){

        pointAmount();
    });

    $("#imgChange").change(function(){
        uploadImage(this);
    });


    $('#product-type-id').change(function(){

        pointAmount();
        

    });

        function pointAmount(){

            prodVal = $('#product-type-id').val();
        console.log('here');
        console.log(prodVal);
        if (prodVal == 3)
        {
           
            $('#input-points').prop("disabled", true);
            $('#input-amount').prop("disabled", false);
            // $('#input-points').prop('required',false);
            $('#input-points').removeAttr('required');
            // $('#input-amount').Attr('required');
            $('#input-amount').prop('required',true);
            $('#input-points').val();
            $('#amount_div').show();
            $('#points_div').hide();
        }    
        else
        {
            $('#input-points').prop("disabled", false);  
            $('#input-amount').prop("disabled", true);
            // $('#input-amount').prop('required',false);
            $('#input-points').prop('required',true);
            $('#input-amount').removeAttr('required');
            // $('#input-points').Attr('required');
            $('#input-amount').val();
            $('#points_div').show();
            $('#amount_div').hide();
        }

        }


    </script>