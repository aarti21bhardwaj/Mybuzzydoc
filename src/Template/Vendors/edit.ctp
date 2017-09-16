<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Practice Information') ?></h5>
            </div>
            <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> You can edit your practice name and add your logo. The logo you add here will show up on the patient portal.
                            </strong>    
                        </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendor, ['data-toggle'=>'validator','class' => 'form-horizontal','enctype'=>"multipart/form-data"]) ?>
                <div class="form-group">

                    <?= $this->Form->label('org_name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('org_name', ['label' => false,'class' => ['form-control']]); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= $this->Form->label('welcome_message', __('Welcome Message'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('welcome_message', ['label' => false,'class' => ['form-control']]); ?>
                    </div>
                </div>

                <?php if(!$topHeader): ?>
                  <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="form-group">
                      <?= $this->Form->label('min_deposit', __('Minimum Deposit'), ['class' => ['col-sm-2', 'control-label']]); ?>
                      <div class="col-sm-10">
                         <?= $this->Form->input('min_deposit', ['label' => false,'min' => 0, 'class' => ['form-control']]); ?>
                      </div>
                  </div>
                  <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="form-group">
                      <?= $this->Form->label('threshold_value', __('Threshold Value'), ['class' => ['col-sm-2', 'control-label']]); ?>
                      <div class="col-sm-10">
                         <?= $this->Form->input('threshold_value', ['label' => false,'min' => 0, 'class' => ['form-control']]); ?>
                      </div>
                  </div>
                <?php endif; ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('image', __('Practice Logo'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-4">
                        <div class="img-thumbnail">
                            <?= $this->Html->image($vendor->image_url, array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
                        </div>
                        <br> </br>
                        <?= $this->Form->input('image_name', ['accept'=>"image/*",'label' => false,['class' => 'form-control'],'type' => "file",'id'=>'imgChange'] ); ?>
                        <i class="fa fa-lg fa-info-circle"></i><strong> The file extensions that you may add here are JPG, PNG and JPEG. 
                            <strong>
                    </div> 
                </div>
                <?php if(!$topHeader): ?>
                  <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                      <div class="col-sm-10">
                              <label class="col-sm-offset-6">
                                  <?= $this->Form->checkbox('is_legacy', ['label' => false]); ?> 
                                 Legacy Client
                              </label>
                     </div>
                     </div>
                     <!-- <div class="form-group">
                      <?= $this->Form->label('is_legacy', __('Legacy Client'), ['class' => ['col-sm-2', 'control-label']]); ?>
                      <div class="col-sm-4">
                         <?= $this->Form->input('is_legacy', ['label' => false, 'class' => ['form-control']]); ?>
                      </div> -->
                  
                   <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="form-group">
                      <div class="col-sm-10">
                              <label class="col-sm-offset-6">
                                  <?= $this->Form->checkbox('status', ['label' => false]); ?> 
                                 Active
                              </label>
                     </div>
                     </div>
                  <?php /*<?= $this->Inspinia->horizontalRule(); ?>
                  <div class="form-group">
                          <?= $this->Form->label('role_id', __('Role'), ['class' => ['col-sm-2', 'control-label']]); ?>
                          <div class="col-sm-10">
                         <?= $this->Form->input('user.role_id', ['label' => false, 'class' => ['form-control'], 'options' => $roles]); ?>
                      </div>
                  </div> */?>
                <?php endif; ?>  
                <?= $this->Form->hidden('template_id', ['value' => $vendor->template_id]); ?>
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

<style type ="text/style">

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
</style>

<!-- /*
     * @method uploadImage
       @return null
       */  -->

       <script type="text/template" id="editBreadCrumb">
        <ol class="breadcrumb" id="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li>
            <strong>
              <?= $this->Html->link(__('Practice Information'), ['controller'=>'Vendors','action' => 'edit', $topHeader['vendor_id']]) ?>
            <strong>
            </li>
           
        </ol>
       </script>
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


    $("#imgChange").change(function(){
        uploadImage(this);
    });


    $('document').ready(function(){

      $('#pageTitle').html("Practice Information");
      <?php if($topHeader): ?>
        $('#breadcrumb').html(document.getElementById('editBreadCrumb').innerHTML);
      <?php endif;?>
      
    });



    </script>