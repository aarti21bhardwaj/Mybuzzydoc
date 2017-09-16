<?= $this->Html->script(['referral-tier-perks']) ?>
<div class="row" ng-app="referralTierPerks" ng-controller="ReferralTierPerksController as Perks">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Referral Tier Perk') ?></h5>
                <div class="text-right">
                    <button ng-click = "Perks.referralTiers(vendorDropDown)" class ="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
            <div class="ibox-content">
                
                <?= $this->Form->create("", ['data-toggle'=>'validator','class' => 'form-horizontal', 'name' => 'referralTierPerk'])?>
                <div class="form-group" ng-if="vendorId == 1">
                    <?= $this->Form->label('vendor', __('Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->select('vendor_id', [], 
                                [
                                    'label' => false, 
                                    'required' => true, 
                                    'class' => 'form-control',
                                    'ng-model' => 'vendorDropDown',
                                    'ng-change' => 'Perks.referralTiers(vendorDropDown)',
                                    'ng-options' => 'vendor.id as vendor.org_name for vendor in vendors',
                                    'empty' => '--Select a practice--'
                                ]
                            ); 
                        ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group" ng-if = "!noTiers">
                    <?= $this->Form->label('referralTiers', __('Referral Tier'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                        <?= $this->Form->select('referral_tier_id', [], 
                                    [
                                        'label' => false, 
                                        'required' => true,
                                        'class' => 'form-control',
                                        'ng-model' => "request.referral_tier_id",
                                        'ng-options' => 'tier.id as tier.name for tier in referralTiers',
                                        'empty' => '--Select a Referral Tier--'
                                    ]
                                ); 
                        ?>
                    </div>
                </div>
                <div ng-if = "noTiers" class="col-sm-offset-2">
                    <strong>No referral tiers are present for this Practice. <a href="{{referralTierUrl}}" id="newTier" target="_blank">Click here</a> to add referral tiers for this Practice.</strong>
                </div> 
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('perk', __('Perk'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                    <?= $this->Form->input("perk", array(
                                        "label" => false, 
                                        'required' => true,
                                        'type' => 'text',
                                        'ng-model' => 'request.perk',
                                        "class" => "form-control"));
                    ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4">
                        <?= $this->Form->button(__('Submit'), ['ng-disabled' => 'referralTierPerk.$invalid || saveButton', 'type'=>'button', 'ng-click' => 'savePerk()','class' => ['btn', 'btn-primary']])  ?>
                        <?= $this->Html->link('Cancel', $this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php
    echo 'var vendorId = "'.$vendorId.'";';
?>
</script>