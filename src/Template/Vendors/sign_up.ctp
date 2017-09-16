<div class="row">
    <div class="col-md-9">
        <div class="ibox float-e-margins">
            <?= $this->Form->create($vendor, ['data-toggle'=>"validator",'class' => 'form-horizontal']) ?>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading" class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" style="cursor:pointer;">
                        <h3><?= __('Create Account') ?></h3>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="form-group">
                            
                            <?= $this->Form->label('org_name', __('Organization Name'), ['class' => ['col-sm-2', 'control-label']]); ?>

                            <div class="col-sm-10">
                               <?= $this->Form->input('org_name', ['label' => false,'class' => ['form-control']]); ?>
                            </div>     
                        </div>
                        
                           <div class="form-group">
                            <?= $this->Form->label('last_name', __('Last Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->input('user.last_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?= $this->Form->label('first_name', __('First Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->input('user.first_name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('email', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->input('user.email', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                         <div class="form-group">
                            <?= $this->Form->label('password', __('Password'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->input('user.password', ['label' => false, 'data-minlength' => 8, 'required' => true,'class' => ['form-control']]); ?>
                               <div class="help-block">Minimum of 8 characters</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('phone', __('Phone'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->input('user.phone', array(
                                                        "label" => false,
                                                        'required' => true,
                                                        'type' => "text",
                                                        'pattern' => "(\d+)",
                                                        'data-minlength' => 10,
                                                        'maxlength' => 15,
                                                        "placeholder" => "1(800)233-2742",
                                                        "class" => "form-control"));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 col-sm-offset-2">
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true">Next</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="panel panel-default payments-method">
                    <div class="panel-heading" class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" style="cursor:pointer;">
                        <div class="pull-right">
                            <i class="fa fa-cc-amex text-success"></i>
                            <i class="fa fa-cc-mastercard text-warning"></i>
                            <i class="fa fa-cc-discover text-danger"></i>
                            <i class="fa fa-cc-visa text-info"></i>
                        </div>
                        <h3><?= __('Payment Details') ?></h3>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>CARD NUMBER</label>
                                            <div class="input-group">
                                                <input class="form-control" name="cc[card_number]" placeholder="Valid Card Number" required="" type="text" data-minlength="15" maxlength="16" onkeypress="disAllowDotInIntegerInput(event);" pattern = "(\d+)" onpaste="return false;">
                                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label>CVV/CVC</label>
                                            <div class="input-group">
                                                <input class="form-control" name="cc[cvv]" placeholder="123" required="" type="text" data-minlength="3" maxlength="4" onkeypress="disAllowDotInIntegerInput(event);" pattern = "(\d+)" onpaste="return false;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 col-md-2">
                                        <div class="form-group">
                                            <label>MONTH</label>
                                            <input class="form-control" name="cc[expiry_month]" placeholder="MM" required="" data-minlength="2" maxlength="2" min="1" max="12" onkeypress="disAllowDotInIntegerInput(event);" onpaste="return false;" type="number">
                                        </div>
                                    </div>

                                    <div class="col-xs-2 col-md-2">
                                        <div class="form-group">
                                            <label>YEAR</label>
                                            <input class="form-control" name="cc[expiry_year]" placeholder="YYYY" required="" <?= 'min = "'.$currentYear.'"' ?> data-minlength="4" maxlength="4" onkeypress="disAllowDotInIntegerInput(event);" onpaste="return false;" type="number">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4">
                                        <div class="form-group">
                                            <label>ZIP</label>
                                            <input class="form-control" name="cc[postal_code]" placeholder="90210" required="" data-minlength="5" maxlength="7" onkeypress="disAllowDotInIntegerInput(event);" onpaste="return false;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8 col-md-8">
                                        <div class="form-group">
                                            <label>NAME ON THE CARD</label>
                                            <input class="form-control" name="cc[name]" required="" onkeypress="disAllowDotInIntegerInput(event);" type="text">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-1">
                                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                            </div>
                        </div> 
                    </div>
                    </div>
                </div>
            </div>
                <?= $this->Form->end() ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Cart Summary</h5>
            </div>
            <div class="ibox-content">
                <span>
                    Total
                </span>
                <h2 class="font-bold">
                    $100.00
                </h2>
            </div>
        </div>
    </div>
</div>