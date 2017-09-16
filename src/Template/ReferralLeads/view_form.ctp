<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Hello '.$referralLead->first_name.'! Your form has sucessfully been submitted') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($referralLead, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>

                <div class="form-group">
                    <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?= $referralLead->first_name ?>   
                    </div>
                </div>


                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('last_name', __('Last Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $referralLead->last_name ?>
                    </div>
                </div>


                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $referralLead->email ?>
                    </div>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Phone'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $referralLead->phone ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Preferred Time To Talk'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                
                        <?= $referralLead->preferred_talking_time ?>
                    </div>
                </div>  
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('status', ['checked' => true,'disabled' => true,'id'=>'check_status','label' => false]); ?>
                            <small>(I agree to be contacted on the number above.)</small>
                        </label>
                    </div>
                </div>
                  
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>