<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            
            <div class="ibox-content">
                <?= $this->Form->create($referralLead, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>

                <div class="form-group">
                    <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("first_name", array(
                                        "label" => false, 
                                        'required' => true,
                                        'placeholder' => 'Your First Name',
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>


                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('last_name', __('Last Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("last_name", array(
                                        "label" => false, 
                                        'required' => true,
                                        'placeholder' => 'Your Last Name',
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>


                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("email", array(
                                        "label" => false,
                                        'type' => 'email',
                                        'placeholder' => 'abc@xyz.com',
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
                                        "minlength" => 10,
                                        "maxlength" => 20,
                                        /*'placeholder' => '9871822748',*/
                                        "class" => "form-control"));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Preferred Time To Talk'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                
                        <?php $options = ['10AM - 12 Noon' => '10AM - 12 Noon',
                                  '2PM - 4PM' => '2PM - 4PM',
                                  '4PM - 6PM' => '4PM - 6PM',
                                  '6PM - 8PM'=>'6PM - 8PM'
                                  ];

                                echo $this->Form->input('preferred_talking_time',array(
                                        "label" => false,
                                        "empty" => "--Please Select--",
                                        "options" => $options,
                                        "class" => "form-control")); 
                        ?>
                    </div>
                </div>  
               
                <?= $this->Form->input("redirectTo", array(
                                        "label" => false, 
                                        "type" => 'hidden',
                                        "value" => $redirectTo));?>
               
                

               
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-5">
                        <?= $this->Form->button(__('Submit'), ['id'=>'check_submit','class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>