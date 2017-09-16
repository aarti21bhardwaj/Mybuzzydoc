<?= $this->Html->script(['tours/instantReward-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = "addInstantGiftCoupon"><?= __('New Instant Rewards Settings') ?></h5>
                <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="alert alert-success"> 
                    <strong> NOTICE: Instant Rewards will still be available on a patient's account if corresponding points are deleted.
                    </strong>    
                </div>
                <?= $this->Form->create($vendorInstantGiftCouponSetting, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
               <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?php } ?>


                <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="ibox-title">
                        <h7><?= __('Set Threshold') ?></h7>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" id = "amtSpent">
                            Amount Spent
                            <br>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('amount_spent_threshold', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>1,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="col-sm-2 control-label" id = "pointsEarned">
                            Points Earned
                            <br>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('points_earned_threshold', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>



                    <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="ibox-title">
                        <h7><?= __('Set Expiry') ?></h7>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" id = "timePeriodForAchievingThreshold">
                            Time period for achieving threshold
                            <br>
                            <small class="text-navy">(In number of hours)</small>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('threshold_time_period', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>1,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="col-sm-2 control-label" id = "instantRewardsAvailableForRedemption">
                            Expiry after redemption period
                            <br>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('redemption_expiry', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id'=>'submit']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

