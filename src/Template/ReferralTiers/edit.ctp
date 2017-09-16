<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Referral Tier') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($referralTier, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                    <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('name', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('referrals_required', __('Referrals Required'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->input("referrals_required", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type'=>'number',
                                        "class" => "form-control",
                                        "min" => 1,
                                        'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                        'onpaste'=>'return false;'
                                    ));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('points', __('Points'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->input("points", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type' => 'number',
                                        "class" => "form-control",
                                        'min'=> 0));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('gift_coupon', __('Gift Coupon'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->select("referral_tier_gift_coupon.gift_coupon_id", $giftCoupons, 
                                                [
                                                    "label" => false, 
                                                    "class" => "form-control",
                                                    'empty'=> 'none'
                                                ]
                        );
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel', $this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>