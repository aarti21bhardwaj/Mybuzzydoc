<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Referral Form') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($referral, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                <!-- <div class="form-group">
                  
                    <?= $this->Form->label('name', __('Select Vendor'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['onchange'=>"get_templates(this)",'empty' => '--Please Select--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div> -->
                <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Select Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['onchange'=>"get_templates(this)",'empty' => '--Please Select--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>
                

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Refer From'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->input("refer_from", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type' => 'email',
                                        'placeholder' => 'abc@xyz.com',
                                        "class" => "form-control"));
                    ?>

                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Refer To'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("refer_to", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type' => 'email',
                                        'placeholder' => 'abc@xyz.com',
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Template'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->select('get_template_name', [''=>'Please Select'], ['id'=>'template_options', 'class' => 'form-control', 'onchange' => 'get_templateData(this)'])?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                <?= $this->Form->label('name', __('Subject'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("subject", array(
                                        "label" => false, 
                                        'required' => true,
                                        'id' => 'subject',
                                        "class" => "form-control"));
                        ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Description'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->textarea('description', ['id'=>'description','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>


                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("name", array(
                                        "label" => false, 
                                        'required' => true,
                                        'placeholder' => '',
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Phone'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("phone", array(
                                        "label" => false, 
                                        "type" => 'tel',
                                        "maxlength" => 10,
                                        /*'placeholder' => '1(800)233-2742',*/
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['id'=>'check_status','label' => false]); ?> Send Me A Copy
                        </label>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['id'=>'check_submit','class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['id'=>'check_cancel','class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>