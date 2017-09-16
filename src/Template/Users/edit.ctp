
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5><?= __('Edit User') ?></h5>
        <?php if($loggedInUser['role_id'] != 3 && $loggedInUser['id'] != $user->id): ?>
          <div class="text-right">
              <button id="resetPasswordRequest" onclick= "sendResetPasswordRequest('<?= $user->email ? $user->email : false ?>')" type = "button" class = "btn-success btn">Send Reset Password Request</button>
          </div>
        <?php endif; ?>
      </div>
      <div class="ibox-content">
        <?= $this->Form->create($user, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
        <div class="form-group">

          <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
          <div class="col-sm-10">
            <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'id' => 'vendorId' ,'class' => ['form-control']]); ?>
          </div>
        </div>
        <?= $this->Inspinia->horizontalRule(); ?>
        <div class="form-group">
          <?= $this->Form->label('idle_timer', __('Auto signout'), ['class' => ['col-sm-2', 'control-label']]); ?>
          <div class="col-sm-10">
          <input class="js-switch_3" style="display: none;" data-switchery="true" type="checkbox">
            <?= $this->Form->input('idle_timer', ['label' => false,'type'=>'checkbox', 'class' => ['form-control', 'js-switch']]); ?>
          </div>
        </div>
        <?= $this->Inspinia->horizontalRule(); ?>
        <div class="form-group">
          <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Define the role each staff member will have: Staff Admin or Staff Manager." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Roles</label>
          <div class="col-sm-10">
            <?= $this->Form->select('role_id', $roles, ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
          </div>
        </div>
        <div id="vendorLocationDiv">
          <?= $this->Inspinia->horizontalRule(); ?>
          <div class="form-group">
            <?= $this->Form->label('vendor_location_id', __('Practice Location'), ['class' => ['col-sm-2', 'control-label']]); ?>
            <div class="col-sm-10">
              <!-- <?= $this->Form->select('vendor_location_id', $locations, ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
              <select id="vendorLoc" name="vendor_location_id"  class="form-control" required="required">
                  <option value="">--Select One--</option>
              </select>
            </div>
          </div>
        </div>
        <?= $this->Inspinia->horizontalRule(); ?>
        <div class="form-group">
          <?= $this->Form->label('name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
          <div class="col-sm-10">
            <?= $this->Form->input('first_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
          </div>
        </div>
        <?= $this->Inspinia->horizontalRule(); ?>
        <div class="form-group">
          <?= $this->Form->label('name', __('Last Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
          <div class="col-sm-10">
            <?= $this->Form->input('last_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
          </div>
        </div>
        <?= $this->Inspinia->horizontalRule(); ?>
        <div class="form-group">
          <?= $this->Form->label('name', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
          <div class="col-sm-10">
            <?= $this->Form->input("email", array(
              "label" => false,
              'required' => true,
              "class" => "form-control"));
              ?>
              <!-- <?= $this->Form->input('email', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
            </div>
          </div>
          <?= $this->Inspinia->horizontalRule(); ?>
          <div class="form-group">
            <?= $this->Form->label('name', __('Phone'), ['class' => ['col-sm-2', 'control-label']]); ?>
            <div class="col-sm-10">
              <?= $this->Form->input("phone", array(
                "label" => false,
                'required' => true,
                "placeholder" => "1(800)233-2742",
                "class" => "form-control"));
                ?>
                <!-- <?= $this->Form->input('phone', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
              </div>
            </div>
            <?= $this->Form->hidden('userId',['value' => $user->id]);?>
            <?= $this->Inspinia->horizontalRule(); ?>
            <div class="form-group">
              <?= $this->Form->label('name', __('Username'), ['class' => ['col-sm-2', 'control-label']]); ?>
              <div class="col-sm-10">
                <?= $this->Form->input("username", array(
                  "label" => false,
                  'required' => true,
                  "class" => "form-control"));
                  ?>
                  <!-- <?= $this->Form->input('username', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
                </div>
              </div>
              <?php if($loggedInUser['id'] == $user->id){
                echo $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                  <?= $this->Form->label('name', __('Password'), ['class' => ['col-sm-2', 'control-label']]); ?>
                  <div class="col-sm-10">
                    <div class="">
                      <a data-toggle="modal" id="changePasswordButton" class="btn btn-primary" href="#changePasswordModal">Change Password</a>
                    </div>
                  </div>
                </div>
                <?php } ?>
                <?php if($loggedInUser['id'] != $user->id){ ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                  <div class="col-sm-10">
                    <label class="col-sm-offset-6">
                      <?= $this->Form->checkbox('status', ['label' => false]); ?> Active
                    </label>
                  </div>
                </div>
                <?php }?>

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



        <div class="modal fade" tabindex="-1" role="dialog" id="changePasswordModal">
          <div class="modal-dialog" role="document">
            <?= $this->Form->create(null, ['class' => 'form-horizontal','data-toggle'=>"validator"]) ?>
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= __('CHANGE_PASSWORD')?></h4>
                 <!--  <strong>Password complexity:</strong><br>
                  Must not contain three or more contiguous characters of your account name or full name.<br>
                  The password needs to be set six times before it can be reused. -->
              </div>

              <div class="modal-body">
                    <strong>Password complexity:</strong><br>
                  - Must not contain three or more contiguous characters of your account name or full name.<br>
                 - The password needs to be set six times before it can be reused.<br><br>
                <div class="alert" id="rsp_msg" style='display: none;'>

                </div>
                <div class="form-group">
                  <?= $this->Form->label('name', __('Old Password'), ['class' => ['col-sm-4', 'control-label']]); ?>
                  <div class="col-sm-8">
                    <?= $this->Form->input("old_pwd", array(
                      "label" => false,
                      'required' => true,
                      'id'=>'old_pwd',
                      "type"=>"password",
                      "class" => "form-control",'data-minlength'=>8,
                      'placeholder'=>"Enter Old Password"));
                      ?>
                      <div class="help-block with-errors"><?= __('PASSWORD_LENGTH')?></div>
                    </div>
                  </div>

                  <div class="form-group">
                    <?= $this->Form->label('name', __('New Password'), ['class' => ['col-sm-4', 'control-label']]); ?>
                    <div class="col-sm-8">
                      <?= $this->Form->input("new_pwd", array(
                        "label" => false,
                        'id'=>'new_pwd',
                        "type"=>"password",
                        'required' => true,
                        "class" => "form-control",'data-minlength'=>8,
                        'placeholder'=>"Enter New Password"));
                        ?>
                        <div class="help-block with-errors"><?= __('PASSWORD_LENGTH')?></div>
                      </div>
                    </div>

                    <div class="form-group">
                      <?= $this->Form->label('name', __('Confirm New Password'), ['class' => ['col-sm-4', 'control-label']]); ?>
                      <div class="col-sm-8">
                        <?= $this->Form->input("cnf_new_pwd", array(
                          "label" => false,
                          "type"=>"password",
                          'id'=>'cnf_new_pwd',
                          'required' => true,
                          "class" => "form-control",'data-minlength'=>8,'data-match'=>"#new_pwd",'data-match-error'=>"__('MISMATCH')",'placeholder'=>"Confirm Password"));
                          ?>
                          <div class="help-block with-errors"><?= __('PASSWORD_LENGTH')?></div>
                        </div>
                      </div>


                    </div>
                    <div class="modal-footer text-center">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"saveUserPassword"]) ?>
                    </div>
                    <?= $this->Form->end() ?>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->

<script type="text/javascript">
  <?php 
    echo 'var locations = '.json_encode($locations).';';
    echo 'var vendorId = '.$user['vendor_id'].';';
    if($user['vendor_location_id']){
      echo 'var vendorLocationId = '.$user['vendor_location_id'].';';
    }else{
      echo 'var vendorLocationId = 0;';
    }
  ?>

 function sendResetPasswordRequest(email){
    if(!email){
      swal("Email is required", "No email is saved for this user.", "error");
    }else{

      $.ajax({
        url: host+"api/users/resetPasswordLink/",
        headers:{"accept":"application/json"},
        dataType: 'json',
        data:{
          "email":email
        },
        type: "post",
        success:function(data){
          swal("Reset Link Sent!", data.response.message, "success");
        },
        error:function(response){
          swal("Error", response.message, "error");
        }
      });
    }
 }

 $(document).ready(function(){
    
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, { color: '#1AB394' });
    //on page load, print the location options of the vendor associated to the user
    if(vendorId == 1){
        $('#vendorLocationDiv').hide();
        $('#vendorLoc').removeAttr('required');
        $('#vendorLoc').val(0); 
    }else{
        $('#vendorLocationDiv').show();
        $('#vendorLoc').attr('required', 'required');
    }
    var options = [];
    for(x in locations[vendorId]){
      if(x == vendorLocationId){
        options.push('<option value ="'+x+'" selected>'+locations[vendorId][x]+'</option>');
      }else{
        options.push('<option value ="'+x+'">'+locations[vendorId][x]+'</option>');
      }
    }
    $("#vendorLoc").html(options);

    //if the associated vendor changes, print the vendor locations of the new vendor
    $("#vendorId").change(function () {
        vendorId = $(this).val();
        if(vendorId == 1){
            $('#vendorLocationDiv').hide();
            $('#vendorLoc').removeAttr('required');
            $('#vendorLoc').val(0);
        }else{
            $('#vendorLocationDiv').show();
            $('#vendorLoc').attr('required', 'required');
        }
        var options= []; 
        for(x in locations[vendorId]){
          options.push('<option value ="'+x+'">'+locations[vendorId][x]+'</option>');
        }
        $("#vendorLoc").html(options);
    });
 });

</script>
