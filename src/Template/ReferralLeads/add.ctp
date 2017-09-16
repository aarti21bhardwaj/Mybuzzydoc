<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Hello '.$referralData->first_name.'! You have been referred by '.$referralData->refer_from) ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($referralLead, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>

                <div class="form-group">
                    <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("first_name", array(
                                        "label" => false, 
                                        'required' => true,
                                        'placeholder' => 'Your First Name',
                                        'value' => $referralData->first_name,
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
                                        'value' => $referralData->last_name,
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
                                        'required' => true,
                                        'type' => 'email',
                                        'placeholder' => 'abc@xyz.com',
                                        'value' => $referralData->refer_to,
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
                                        'required' => true, 
                                        "type" => 'tel',
                                        "minlength" => 10,
                                        "maxlength" => 20,
                                        /*'placeholder' => '9871822748',*/
                                        'value' => $referralData->phone,
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
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['required' => true,'id'=>'check_status','label' => false]); ?>
                            <small>(I agree to be contacted on the number above.)</small>
                        </label>
                    </div>
                </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                
                <div class="form-group">
                        <label class="col-sm-offset-1">
                I'm part of BuzzyDoc Office! BuzzyDoc is fun patient rewards program where the doctor gives you points for being a good patient and continuing to come back!
                        </label>
                </div>
                

                <!-- <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Vendor'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input("vendor_id", array(
                                        "label" => false,
                                        'empty' => '--Please Select--',
                                        'options' => $vendors,
                                        "class" => "form-control"));
                    
                    ?>
                    </div>
                </div>  --> 
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-5">
                        <?= $this->Form->button(__('Submit'), ['id'=>'check_submit','class' => ['btn', 'btn-primary']]) ?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>