<?= $this->Html->script(['vendor-referral-settings', 'tours/referralSetting-tour']) ?>
<div class="row" ng-app="referralSettings" ng-controller="ReferralSettingsController as Settings">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id='addReferralSettings'><?= __('Add Practice Referral Setting') ?></h5>
                <div class="text-right">
                    <button ng-click = "Settings.refresh()" class ="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button>
                    <button class="btn btn-primary startTour" type="button" >
                        <i class="fa fa-play"></i> Start Tour
                    </button>
                </div>
            </div>
            <div class="ibox-content">


                <form data-toggle="validator" name="referralSetting" class="form-horizontal" ng-submit="Settings.submitForm()">

                    <div class="form-group" ng-if="showVendors">

                        <label name="vendor" class="col-sm-2 control-label">Select Practice</label>
                        <div class="col-sm-10">
                            <select ng-model="request.vendor_id" ng-options= "vendor.id as vendor.org_name for vendor in vendors" required="true" label = "false" class="form-control" ng-change = "Settings.vendorChange()"> 
                                <option value="">--Please Select--</option>
                            </select>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>

                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label"  name="referralLevelName" id='levelName' >Referral Level Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="referal_level_name" ng-model="request.referral_level_name" label="false" required="true" class="form-control">
                        </div>
                    </div>

                    <?= $this->Inspinia->horizontalRule(); ?>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" name="referrerAwardPoints" data-toggle="tooltip" data-placement="top" title="Points awarded to a patient for making a referral." id='referralPoints'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Referrer Award Points</label>
                        <div class="col-sm-10">
                            <input type="number" name="referrer_award_points" ng-model="request.referrer_award_points" label="false" required="true" class="form-control" min="0" max="10000" placeholder="Max 10000 points" onkeypress="disAllowDotInIntegerInput(event);">
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" name="referreeAwardPoints" data-toggle="tooltip" data-placement="top" title="Points awarded to the referred patient when they start treatment with your practice." id='refereePoints'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Referree Award Points</label>
                        
                        <div class="col-sm-10">
                            <input type="number" name="referree_award_points" ng-model="request.referree_award_points" label="false" required="true" class="form-control" min="0" max="10000" placeholder="Max 10000 points" onkeypress="disAllowDotInIntegerInput(event);">
                        </div>
                    </div>

                    <?= $this->Inspinia->horizontalRule(); ?>

                    <div class="form-group">

                        <label name="gift_coupon" class="col-sm-2 control-label" id='refereeGiftCoupon'> Referree Gift Coupon</label>
                        <div class="col-sm-10">
                            <select ng-model="request.referral_setting_gift_coupon.gift_coupon_id" ng-options= "coupon.id as coupon.description for coupon in giftCoupons" label = "false" class="form-control" ng-if= "giftCoupons != false"> 
                                <option value="">None</option>
                            </select>
                            <strong ng-if= "giftCoupons == false">No gift coupons are present for this practice. <a href="{{giftCouponUrl}}" id="newGiftCoupon" target="_blank">Click here</a> to add gift coupons for this practice.</strong>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>

                    <div class="form-group">
                        <div class="col-sm-10">
                            <label class="col-sm-offset-6" data-toggle="tooltip" data-placement="top" title="You can activate/deactivate referral levels. Only the active levels will be available when awarding points for referrals." data-original-title="Tooltip on top"> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>
                                <input type="checkbox" ng-init="request.status = true" name="status" ng-model="request.status" label="false">Status
                            </label>
                        </div>
                    </div>
                
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn-primary btn" ng-disabled = "referralSetting.$invalid" id='saveReferral'>Submit</button>
                            <?= $this->Html->link('Cancel',$this->request->referer(),['id'=>'check_cancel','class' => ['btn', 'btn-danger']]);?>
                        </div>
                    </div>     
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php

    echo 'var vendorId = "'.$vendorId.'";';
?>
</script>