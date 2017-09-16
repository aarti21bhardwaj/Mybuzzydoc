<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Edit Tier') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($tier, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                    <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <div class="form-group">

                    <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
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
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Minimum amount a patient needs to spend to reach a tier." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Lowerbound</label>
                    <div class="col-sm-10">
                    <?= $this->Form->input("lowerbound", array(
                                        "label" => false, 
                                        'required' => true,
                                        'disabled' => true,
                                        'type' => 'number',
                                        "class" => "form-control"));
                    ?>
                    <?= $this->Form->input("lowerbound", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type'=>'hidden',
                                        "class" => "form-control",
                                        'min'=> 0));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title=" Maximum amount that can be spent in a tier." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Upperbound</label>
                    <div class="col-sm-10">
                    <?= $this->Form->input("upperbound", array(
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
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="The cashback (in points) a patient receives is based on this % multiplier and the amount they spend in your office." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Multiplier(%)</label>
                    <div class="col-sm-10">
                    <?= $this->Form->input("multiplier", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type' => "number",
                                        "class" => "form-control",
                                        'min'=> 0));
                    ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('gift_coupon', __('Gift Coupon'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->select("tier_gift_coupon.gift_coupon_id", $giftCoupons, 
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