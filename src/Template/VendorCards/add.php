<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class = "col-sm-offset-6"><?= __('Add Practice') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($vendor, ['data-toggle'=>"validator",'class' => 'form-horizontal', 'enctype'=>"multipart/form-data"]) ?>
                <div class="form-group">
                    
                    <?= $this->Form->label('org_name', __('Organization Name'), ['class' => ['col-sm-2', 'control-label']]); ?>

                    <div class="col-sm-10">
                       <?= $this->Form->input('org_name', ['label' => false,'class' => ['form-control']]); ?>
                    </div>     
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('name', __('Practice Plan'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">

                       <?= $this->Form->select('vendor_plans.plan_id', $plans ,['label' => false, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('min_deposit', __('Minimum Deposit'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('min_deposit', ['label' => false,'min' => 0, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <!--threshold value-->
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('threshold_value', __('Threshold Value'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('threshold_value', ['label' => false,'min' => 0, 'class' => 'form-control']); ?>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('image', __('Image'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-4">
                        <div class="img-thumbnail">
                            <?= $this->Html->image($vendor->image_url, array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
                        </div>
                        <br> </br>
                        <?= $this->Form->input('image_name', ['accept'=>"image/*",'label' => false,'required' => true,['class' => 'form-control'],'type' => "file",'id'=>'imgChange']); ?>
                    </div> 
                </div>

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
                <div class="ibox-title">
                <h5><?= __('Add Staff Admin') ?></h5>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('last_name', __('Last Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('user.last_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('user.first_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('email', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('user.email', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <!-- <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('username', __('Username'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("user.username", array(
                                        "label" => false, 
                                        'required' => true,
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div> -->
                <?= $this->Inspinia->horizontalRule(); ?>
                 <div class="form-group">
                    <?= $this->Form->label('password', __('Password'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('user.password', ['label' => false, 'data-minlength' => 8, 'required' => true,'class' => ['form-control']]); ?>
                       <div class="help-block">Minimum of 8 characters</div>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('phone', __('Phone'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('user.phone', array(
                                                "label" => false,
                                                'required' => true, 
                                                "placeholder" => "1(800)233-2742",
                                                "class" => "form-control"));
                        ?>
                    </div>
                </div>

                <?php /*<?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                        <?= $this->Form->label('role_id', __('Role'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                        <?= $this->Form->input('users[0].role_id', ['label' => false,'required' => true,'class' => ['form-control'], 'options' => $roles]); ?>
                    </div>
                </div> */?>
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
