<?= $this->Html->script(['tier-perks']) ?>
<div class="row" ng-app="tierPerks" ng-controller="TierPerksController as Perks">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Tier Perk') ?></h5>
                <div class="text-right">
                    <button ng-click = "Perks.tiers(vendorDropDown)" class ="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
            <div class="ibox-content">
                
                <?= $this->Form->create("", ['data-toggle'=>'validator','class' => 'form-horizontal', 'name' => 'tierPerk'])?>
                <div class="form-group" ng-if="vendorId == 1">
                    <?= $this->Form->label('vendor', __('Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->select('vendor_id', [], 
                                [
                                    'label' => false, 
                                    'required' => true, 
                                    'class' => 'form-control',
                                    'ng-model' => 'vendorDropDown',
                                    'ng-change' => 'Perks.tiers(vendorDropDown)',
                                    'ng-options' => 'vendor.id as vendor.org_name for vendor in vendors',
                                    'empty' => '--Select a practice--'
                                ]
                            ); 
                        ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group" ng-if = "!noTiers">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Tier level which the patient must achieve to receive the added perk." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Tier</label>
                    <div class="col-sm-10">
                        <?= $this->Form->select('tier_id', [], 
                                    [
                                        'label' => false, 
                                        'required' => true,
                                        'class' => 'form-control',
                                        'ng-model' => "request.tier_id",
                                        'ng-options' => 'tier.id as tier.name for tier in tiers',
                                        'empty' => '--Select a Tier--'
                                    ]
                                ); 
                        ?>
                    </div>
                </div>
                <div ng-if = "noTiers" class="col-sm-offset-2">
                    <strong>No tiers are present for this practice. <a href="{{tierUrl}}" id="newTier" target="_blank">Click here</a> to add tiers for this practice.</strong>
                </div> 
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Privileges/bonus issued to your patients. The perks you add here will be shown on the patient’s account when they achieve the respective tier level." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Perk</label>
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
                        <?= $this->Form->button(__('Submit'), ['ng-disabled' => 'tierPerk.$invalid || saveButton', 'type'=>'button', 'ng-click' => 'savePerk()','class' => ['btn', 'btn-primary']])  ?>
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