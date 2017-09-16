<?= $this->Html->script(['tours/user-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = 'addUsers'><?= __('Add User') ?></h5>
                <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($user, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                    <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'id' => 'vendorId', 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden', 'id' => 'vendorId']); ?>
                <?php } ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Define the role each staff member will have: Staff Admin or Staff Manager." id = 'role'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Roles</label>
                    <div class="col-sm-10">
                       <?= $this->Form->input('role_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div id="vendorLocationDiv">
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group" >
                      <?= $this->Form->label('vendor_location_id', __('Practice Location'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'userLocation']); ?>
                      <div class="col-sm-10">
                        <select id="vendorLoc"  name="vendor_location_id" class="form-control" required="required">
                            <option value="">--Select One--</option>
                        </select>
                      </div>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('First Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'name']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('first_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Last Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'lastname']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('last_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Email'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'email']); ?>
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
                    <?= $this->Form->label('name', __('Phone'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'phoneNo']); ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input("phone", array(
                                                "label" => false,
                                                'required' => true, 
                                                "placeholder" => "1(800)233-2742",
                                                "class" => "form-control"));
                        ?>
                      <!--  <?= $this->Form->input('phone', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Username'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'userName']); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->input("username", array(
                                        "label" => false, 
                                        'required' => true,
                                        "class" => "form-control"));
                    ?>

                       <!-- <?= $this->Form->input('username', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Password'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'password']); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("password", array(
                                                "label" => false,
                                                'data-minlength' => 8, 
                                                'required' => true,
                                                "placeholder" => "",
                                                "class" => "form-control"));
                        ?>
                        <div class="help-block">Minimum of 8 characters</div>
                       <!-- <?= $this->Form->input('password', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?> -->
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['label' => false]); ?> Active
                        </label>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveUser']) ?>
                        <?= $this->Html->link('Cancel', $this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  <?php 
    echo 'var locations = '.json_encode($locations).';';
  ?>

  $(document).ready(function(){
    //on page load, print the location options of the vendor associated to the user
    vendorId = $("#vendorId").val();
    if(vendorId == 1){
        $('#vendorLocationDiv').hide();
        $('#vendorLoc').removeAttr('required');
    }else{
        $('#vendorLocationDiv').show();
        $('#vendorLoc').attr('required', 'required');
    }
    var options = [];
    for(x in locations[vendorId]){
      options.push('<option value ="'+x+'">'+locations[vendorId][x]+'</option>');
    }
    $("#vendorLoc").html(options);

    //if the associated vendor changes, print the vendor locations of the new vendor
    $("#vendorId").change(function () {
        vendorId = $(this).val();
        if(vendorId == 1){
            $('#vendorLocationDiv').hide();
            $('#vendorLoc').removeAttr('required');
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
