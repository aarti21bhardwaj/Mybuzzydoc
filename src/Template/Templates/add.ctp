<?= $this->Html->script(['templates']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins" ng-app="templates" ng-controller="TemplatesController as Templates">
            <div class="ibox-title">
                <h5>{{title}}</h5>
            </div>
            <div class="ibox-content">
                <div ng-show="templateVisible">
                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'tempName']) ?>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Enter a Template name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                            <?= $this->Form->input('name', ['label' => false, 'class' => ['form-control'], 'ng-model' => 'template.name', 'required' => true, 'maxlength'=> '40']); ?>    
                        </div>
                    </div>
                    <br>
                    <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                    <?= $this->Form->label('name', __('Choose Plan'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">

                           <?= $this->Form->select('plan_id', $plans ,['label' => false, 'ng-model' => 'template.template_plans[0].plan_id', 'required' => true, 'class' => ['form-control'], 'empty' => '--Please Select Plan--']); ?>
                        </div>
                    </div>
                    <br><br><br>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Choose Industry'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                         <?= $this->Form->input('industry_id', ['label' => false, 'id' => 'industry_input', 'required' => true, 'ng-model' => 'template.industry_templates[0].industry_id', 'class' => ['form-control'], 'options' => $industry, 'empty' => '--Please Select Industry--' ]); ?>
                        <p><strong>{{message}}</strong></p>
                        </div>
                    </div>
                    <?= $this->Form->end(); ?>
                    <br><br><br>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-5">
                            <?= $this->Form->button(__('Submit'), ['id'=> 'tempName','class' => ['btn', 'btn-primary'], 'ng-click' => 'Templates.createTemplate()', 'type' => 'button', 'ng-disabled'=>'tempName.$invalid']) ?>
                            
                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                        </div>
                    </div>
                    <br><br>
                </div>
                <div class="tabs-container" ng-show="tabVisible">
                    <div class="tabs-left">
                        <ul class="nav nav-tabs">
                            <li class="active">
                            <a data-toggle="tab" href="#tab-1" ng-click = "Templates.tabChange()">Reviews</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-2" ng-click = "Templates.tabChange()">Referrals</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-3" ng-click = "Templates.tabChange()">Promotions</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-4" ng-click = "Templates.tabChange()">Tiers</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-5" ng-click = "Templates.tabChange()">Milestones</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-6" ng-click = "Templates.tabChange()">Gift Coupons</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-7" ng-click = "Templates.tabChange()">Survey</a>
                            </li>
                        </ul>
                        <div class="tab-content ">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <h4><?= __('Review Settings') ?></h4>
                                    <p>
                                        <?= __('*These Points will be given to the patient based on the tasks completed while completing the Review Process for a Clinic') ?>
                                    </p>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'rvwSet', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']) ?>

                                    <div class="form-group">
                                        <?= $this->Form->label('review_points', __('Reviews'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                             <?= $this->Form->input('review_points', ['id' => 'review-points' ,'label' => false, 'type' => 'number', 'min'=> 0,'required' => true,  'class' => ['form-control'], 'ng-model' => 'template.reviews.review_points', 'ng-init' => 'template.reviews.review_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('rating_points', __('Ratings'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('rating_points', ['id' => 'rating-points' ,'label' => false, 'type' => 'number', 'min'=> 0, 'class' => ['form-control'], 'ng-model' => 'template.reviews.rating_points','required' => true, 'ng-init' => 'template.reviews.rating_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('fb_points', __('Facebook'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('fb_points', ['type' => 'number','id' => 'fb-points' ,'label' => false, 'min'=> 0, 'class' => ['form-control'],'required' => true,'required' => true, 'ng-model' => 'template.reviews.fb_points', 'ng-init' => 'template.reviews.fb_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('gplus_points', __('Google Plus'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('gplus_points', ['id' => 'gplus-points' ,'label' => false, 'type' => 'number','min'=> 0, 'class' => ['form-control'],'required' => true, 'ng-model' => 'template.reviews.gplus_points', 'ng-init' => 'template.reviews.gplus_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('yelp_points', __('Yelp'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('yelp_points', ['id' => 'yelp-points' ,'label' => false, 'type' => 'number','min'=> 0, 'class' => ['form-control'],'required' => true, 'ng-model' => 'template.reviews.yelp_points', 'ng-init' => 'template.reviews.yelp_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('ratemd_points', __('RateMD'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('ratemd_points', ['id' => 'ratemd-points' ,'label' => false, 'type' => 'number','min'=> 0, 'class' => ['form-control'],'required' => true, 'ng-model' => 'template.reviews.ratemd_points', 'ng-init' => 'template.reviews.ratemd_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('yahoo_points', __('Yahoo'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('yahoo_points', ['id' => 'yahoo-points' ,'label' => false, 'type' => 'number','min'=> 0, 'class' => ['form-control'],'required' => true, 'ng-model' => 'template.reviews.yahoo_points', 'ng-init' => 'template.reviews.yahoo_points = 0']); ?>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <?= $this->Form->label('healthgrades_points', __('Healthgrades'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                            <?= $this->Form->input('healthgrades_points', ['id' => 'healthgrades-points' ,'label' => false, 'type' => 'number','min'=> 0, 'class' => ['form-control'], 'ng-model' => 'template.reviews.healthgrades_points','required' => true, 'ng-init' => 'template.reviews.healthgrades_points = 0']); ?>
                                            <p><strong>{{save_message}}</strong></p>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <?= $this->Form->end() ?>
                                    <div class="form-group">
                                        <div class="col-sm-3 col-sm-offset-5">
                                            <?= $this->Form->button(__('Save'), ['id'=> 'saveRvw','type' => 'button','ng-disabled'=>'rvwSet.$invalid','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(1)']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <h4 class='col-sm-6'><?= __('Referral Settings') ?></h4>
                                    
                                    <button id= "submit" class="col-sm-3 col-sm-offset-2 pull-right btn btn-primary" ng-click= "Templates.addNew('referrals')" >Add Another Level</button>
                                    
                                    <br><br><br>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'refSet']) ?>
                                    <div ng-repeat = "(key, t) in template.referrals">
                                        <button class="close pull-right" type="button" ng-click = "Templates.remove('referrals', $index)">
                                                <span aria-hidden="true">×</span>
                                        </button>
                                        <br>
                                        <div class="form-group" ng-class="refSet.refName{{$id}}.$invalid ? 'has-error' : ''">
                                            <?= $this->Form->label('name', __('Referral Level Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                            <div class="col-sm-10">
                                                <?= $this->Form->input("referral_level_name", array(
                                                                    "label" => false, 
                                                                    'required' => true,
                                                                    "class" => "form-control",
                                                                'ng-model' => 't.referral_level_name',
                                                                'name' => 'refName{{$id}}'));
                                                ?>
                                            </div>
                                        </div>
                                        <br><br><br>
                                        <div class="form-group" ng-class="refSet.refAwrdPts{{$id}}.$invalid ? 'has-error' : ''">
                                            <?= $this->Form->label('name', __('Referrer Award Points'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                            <div class="col-sm-10">
                                               <?= $this->Form->input("referrer_award_points", array(
                                                                "label" => false, 
                                                                'required' => true,
                                                                'type' => "number",
                                                                'min' => 0,
                                                                "class" => "form-control",
                                                                'ng-model' => 't.referrer_award_points',
                                                                'ng-init' => "t.referrer_award_points =  t.referrer_award_points || 0",
                                                                'name' => 'refAwrdPts{{$id}}',
                                                                'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                                'onpaste'=>'return false;'));
                                                ?>
                                            </div>
                                        </div>
                                        <br><br><br>
                                        <div class="form-group" ng-class="refSet.reAwrdPts{{$id}}.$invalid ? 'has-error' : ''">
                                            <?= $this->Form->label('name', __('Referree Award Points'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                            <div class="col-sm-10">
                                               <?= $this->Form->input("referree_award_points", array(
                                                                "label" => false, 
                                                                'required' => true,
                                                                'type' => "number",
                                                                'min' => 0,
                                                                "class" => "form-control",
                                                                'ng-model' => 't.referree_award_points',
                                                                'ng-init' => 't.referree_award_points = t.referree_award_points || 0',
                                                                'name' => 'reAwrdPts{{$id}}',
                                                                'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                                'onpaste'=>'return false;'
                                                                ));
                                            ?>
                                            </div>
                                        </div>
                                        <br><br><br>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <label class="col-sm-offset-6">
                                                    <?= $this->Form->checkbox('status', ['ng-model' => 't.status','ng-init' => 't.status = t.status == true ? true : false' ,'label' => false]); ?> Status
                                                </label>
                                            </div>
                                        </div>
                                        <br>
                                    </div> <!--ng-repeat ends here -->
                                    <?= $this->Form->end() ?>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <p><strong>{{save_message}}</strong></p>
                                        </div>
                                        <br><br>
                                        <div class="col-sm-4 col-sm-offset-4">    
                                            <?= $this->Form->button(__('Save'), ['id'=> 'saveRef','type' => 'button','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(2)', 'ng-disabled'=>'refSet.$invalid || !template.referrals[0]']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>     

                                </div>
                            </div>
                            <div id="tab-3" class="tab-pane">
                                <div class="panel-body">
                                    <h4><?= __('Select Promotions') ?></h4>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'promSet']) ?>
                                        <br><br>
                                        <div class = "row col-sm-offset-2">
                                            <?php foreach ($promotions as $key => $promotion): ?>
                                            
                                                <div class = "col-sm-5">
                                                    <label class = "checkbox-inline">
                                                    <?= $this->Form->checkbox('', ['label' => false, 'ng-model' => 'template.promotions['.$promotion->id.']']); ?> <?= $promotion->name ?>
                                                    </label>
                                                </div>
                                             
                                            <?php endforeach; ?>
                                        </div>
                                    <?= $this->Form->end() ?>
                                    <div class="col-sm-10 col-sm-offset-2">
                                            <p><strong>{{save_message}}</strong></p>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <?= $this->Form->button(__('Save'), ['id'=> 'tempProm','type' => 'button','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(3)', 'ng-disabled'=>'promSet.$invalid']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>     

                                </div>
                            </div>
                            <div id="tab-4" class="tab-pane">
                                <div class="panel-body">
                                    <h4><?= __('Tier Settings') ?></h4>
                                    <button id= "submit" class="col-sm-3 col-sm-offset-2 pull-right btn btn-primary" ng-click= "Templates.addNew('tier')" >Add Another Tier</button>
                                    <br><br><br>
                                    <?= $this->Form->create("", ['name' =>'tierForm','data-toggle'=>'validator','class' => ['form-horizontal']])?>
                                        <div ng-repeat = "(key, tier) in template.tier">
                                            <button class="close" type="button" ng-click = "Templates.remove('tier',$index)">
                                                    <span aria-hidden="true">×</span>
                                            </button>
                                            <br>
                                            <div class="form-group" style="height:34px" ng-class="tierForm.tierName{{$id}}.$invalid ? 'has-error' : ''">
                                                <?= $this->Form->label('name', __('Name'), ['class' => ['col-sm-2', 'control-label' , 'pull-left']]); ?>
                                                <div class="col-sm-9">
                                                   <?= $this->Form->input('name', 
                                                            [
                                                                'label' => false, 
                                                                'required' => true,
                                                                'ng-model' => 'tier.name',
                                                                'name' => 'tierName{{$id}}', 
                                                                'class' => ['form-control']
                                                            ]
                                                        ); 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group" style="height:34px" ng-class="tierForm.tierLwr{{$id}}.$invalid ? 'has-error' : ''">
                                                <?= $this->Form->label('lowerbound', __('Lowerbound'), ['class' => ['col-sm-2', 'control-label', 'pull-left']]); ?>
                                                <div class="col-sm-9">
                                                <?= $this->Form->input("lowerbound", 
                                                        [
                                                            "label" => false, 
                                                            'required' => true,
                                                            'type' => 'number',
                                                            'min' => '{{$index != 0 ? template.tier[key-1].upperbound + 1 : 0}}',
                                                            'max' => '{{$index != 0 ? template.tier[key-1].upperbound + 1 : 0}}',
                                                            'ng-init' => 'tier.lowerbound = ($index == 0 ? 0 : template.tier[key-1].upperbound + 1)',
                                                            'ng-model' => 'tier.lowerbound',
                                                            'name' => 'tierLwr{{$id}}',
                                                            "class" => "form-control",
                                                            'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                            'onpaste'=>'return false;'
                                                        ]
                                                    );
                                                ?>
                                                </div>
                                            </div>
                                            <div class="form-group" style="height:34px" ng-class="tierForm.tierUpr{{$id}}.$invalid ? 'has-error' : ''">
                                                <?= $this->Form->label('upperbound', __('Upperbound'), ['class' => ['col-sm-2', 'control-label', 'pull-left']]); ?>
                                                <div class="col-sm-9">
                                                <?= $this->Form->input("upperbound", 
                                                        [
                                                            "label" => false, 
                                                            'required' => true,
                                                            'type' => 'number',
                                                            "class" => "form-control",
                                                            'min'=> '{{tier.lowerbound + 2}}',
                                                            'ng-init' => 'tier.upperbound = tier.upperbound || (tier.lowerbound + 2)',
                                                            'ng-model' => 'tier.upperbound',
                                                            'name' => 'tierUpr{{$id}}',
                                                            'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                            'onpaste'=>'return false;'
                                                        ]
                                                    );
                                                ?>
                                                </div>
                                            </div>
                                            <div class="form-group" ng-class="tierForm.tierMul{{$id}}.$invalid ? 'has-error' : ''">
                                                <?= $this->Form->label('multiplier', __('Multiplier(%)'), ['class' => ['col-sm-2', 'control-label', 'pull-left']]); ?>
                                                <div class="col-sm-9">
                                                <?= $this->Form->input("multiplier", 
                                                        [
                                                            "label" => false, 
                                                            'required' => true,
                                                            'type' => "number",
                                                            "class" => "form-control",
                                                            'min'=> 0,
                                                            'step' => 0.01,
                                                            'ng-model' => 'tier.multiplier',
                                                            'ng-init' => 'tier.multiplier = tier.multiplier || 0',
                                                            'name' => 'tierMul{{$id}}',
                                                        ]
                                                    );
                                                ?>
                                                </div>
                                            </div>
                                                <div class="form-group" ng-class="tierForm.tierGift{{$id}}.$invalid ? 'has-error' : ''">
                                                <?= $this->Form->label('gift_coupon', __('Gift Coupon'), ['class' => ['col-sm-2', 'control-label', 'pull-left']]); ?>
                                                <div class="col-sm-9">
                                                <?= $this->Form->select("gift_coupon",[], 
                                                        [
                                                            "label" => false, 
                                                            'required' => false,
                                                            "class" => "form-control",
                                                            'ng-model' => 'tier.tier_gift_coupon.gift_coupon_id',
                                                            'name' => 'tierGift{{$id}}',
                                                            'onpaste'=>'return false;',
                                                            'empty' => '--none--',
                                                            'ng-options' => 'gift.gift_coupon_id as gift.gift_coupon.description for gift in giftCoupons',

                                                        ]
                                                    );
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 col-sm-offset-2">
                                                <p><strong>{{save_message}}</strong></p>
                                            </div>
                                            <br><br>
                                            <div class="col-sm-4 col-sm-offset-4">
                                                <?= $this->Form->button(__('Save'), 
                                                    [
                                                        'id'=> 'tempTier',
                                                        'type' => 'button',
                                                        'class' => ['btn', 'btn-primary'], 
                                                        'ng-click' => 'Templates.updateTemplate(4)',
                                                        'ng-disabled'=>'tierForm.$invalid'
                                                    ]
                                                ) ?>
                                                <a href="{{indexLoc}}" id="toIndex" class="btn btn-danger">Cancel</a>
                                            </div>
                                        </div>
                                    <?= $this->Form->end() ?>
                                        
                                </div>
                            </div>
                            <div id="tab-5" class="tab-pane">
                                <div class="panel-body">
                                    <h4><?= __('Select Milestone') ?></h4>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'promSet']) ?>
                                        <br><br>
                                        <div class = "row col-sm-offset-2">
                                            <?php foreach ($milestones as $key => $milestone): ?>
                                                
                                                <div class = "col-sm-5">
                                                    <label class = "checkbox-inline">
                                                    <?= $this->Form->checkbox('', 
                                                            [
                                                                'label' => false, 
                                                                'ng-click'=>'template.milestone = []; template.milestone['.$milestone->id.'] = true',
                                                                'ng-model' => 'template.milestone['.$milestone->id.']'
                                                            ]
                                                        ); 
                                                    ?> <?= $milestone->name ?>
                                                    </label>
                                                </div>
                                             
                                            <?php endforeach; ?>
                                        </div>
                                    <?= $this->Form->end() ?>
                                    <div class="col-sm-10 col-sm-offset-2">
                                            <p><strong>{{save_message}}</strong></p>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <?= $this->Form->button(__('Save'), ['id'=> 'tempMile','type' => 'button','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(5)', 'ng-disabled'=>'promSet.$invalid']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>     

                                </div>
                            </div>
                            <div id="tab-6" class="tab-pane">
                                <div class="panel-body">
                                    <h4><?= __('Select Gift Coupons') ?></h4>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'promSet']) ?>
                                        <br><br>
                                        <div class = "row col-sm-offset-2">
                                            <?php foreach ($gift_coupons as $key => $gift_coupon): ?>
                                                
                                                <div class = "col-sm-5">
                                                    <label class = "checkbox-inline">
                                                    <?= $this->Form->checkbox('', 
                                                            [
                                                                'label' => false, 
                                                                'ng-model' => 'template.gift_coupon['.$gift_coupon->id.']'
                                                            ]
                                                        ); 
                                                    ?> <?= $gift_coupon->description ?>
                                                    </label>
                                                </div>
                                             
                                            <?php endforeach; ?>
                                        </div>
                                    <?= $this->Form->end() ?>
                                    <div class="col-sm-10 col-sm-offset-2">
                                            <p><strong>{{save_message}}</strong></p>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <?= $this->Form->button(__('Save'), ['id'=> 'tempGift','type' => 'button','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(6)', 'ng-disabled'=>'promSet.$invalid']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>     

                                </div>
                            </div>
                            <div id="tab-7" class="tab-pane">
                                <div class="panel-body">
                                    <h4><?= __('Select Surveys') ?></h4>
                                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'promSet']) ?>
                                        <br><br>
                                        <div class = "row col-sm-offset-2" style="height:115px;">
                                            <?php foreach ($surveys as $key => $survey): ?>
                                                
                                                <div class = "col-sm-5">
                                                    <label class = "checkbox-inline">
                                                    <?= $this->Form->checkbox('', 
                                                            [
                                                                'label' => false, 
                                                                'ng-click'=>'template.survey = []; template.survey['.$survey->id.'] = true',
                                                                'ng-model' => 'template.survey['.$survey->id.']'
                                                            ]
                                                        ); 
                                                    ?> <?= $survey->name ?>
                                                    </label>
                                                </div>
                                             
                                            <?php endforeach; ?>
                                        </div>
                                    <?= $this->Form->end() ?>
                                    <div class="col-sm-10 col-sm-offset-2">
                                            <p><strong>{{save_message}}</strong></p>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <?= $this->Form->button(__('Save'), ['id'=> 'tempSurv','type' => 'button','class' => ['btn', 'btn-primary'], 
                                            'ng-click' => 'Templates.updateTemplate(7)', 'ng-disabled'=>'promSet.$invalid']) ?>
                                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>
                                        </div>
                                    </div>     

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
<?php
    echo 'var templateId = "'.$template->id.'";';
?>
</script>