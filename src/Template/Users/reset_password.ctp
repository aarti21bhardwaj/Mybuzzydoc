<div class="passwordBox animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <div class="ibox-content">

                <h2 class="font-bold text-center"><?= __('RESET_PASSWORD')?></h2>
                <div class="ibox-content">
                    <strong>Password complexity:</strong><br>
                  - Must not contain three or more contiguous characters of your account name or full name.<br>
                 - The password needs to be set six times before it can be reused.</div>
                <div class="row">
                <?= $this->Form->create(null, ['class' => 'form-horizontal','data-toggle'=>"validator"]) ?>
                    <div class="col-lg-12">

                     <div class="form-group">
                        <?= $this->Form->label('name', __('New Password'), ['class' => ['col-sm-5', 'control-label']]); ?>
                        <div class="col-sm-7">
                           <?= $this->Form->input("new_pwd", array(
                            "label" => false,
                            'required' => true,
                            'id'=>'new_pwd',
                            "type"=>"password",
                            "class" => "form-control",
                            'data-minlength'=>8,
                            'placeholder'=>"Enter Password"));
                            ?>
                            <div class="help-block with-errors"><?= __('PASSWORD_LENGTH')?></div>
                        </div>
                    </div>
                    <?= $this->Form->hidden('reset-token',['value' => $resetToken]);?>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Confirm Password'), ['class' => ['col-sm-5', 'control-label']]); ?>
                        <div class="col-sm-7">
                           <?= $this->Form->input("cnf_new_pwd", array(
                            "label" => false,
                            'required' => true,
                            "class" => "form-control",
                            'id'=>'cnf_new_pwd',
                            'data-minlength'=>8,
                            'data-match'=>"#new_pwd",
                            "type"=>"password",
                            'data-match-error'=>"Whoops, these don't match",
                            'placeholder'=>"Confirm Password"));
                            ?>
                            <div class="help-block with-errors"><?= __('PASSWORD_LENGTH')?></div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>
                <?= $this->Form->end() ?>

            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
