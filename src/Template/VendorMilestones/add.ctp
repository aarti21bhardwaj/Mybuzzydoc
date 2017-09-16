<?= $this->Html->script(['milestone', 'tours/milestone-tour']) ?>
<div ng-app="milestone" ng-controller="MilestoneController as Miles">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5 id="addMilestoneTitle"><?= __('Add Milestone Program') ?></h5>
                    <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                    </div>
                </div>
                 <div class="alert alert-success"> 
                            <strong> The milestone program can be used alongside your compliance survey.
                            You have the option to reward your patients (with a bonus) when they complete a perfect score on the compliance survey a set number of times. You can set this number and the reward bonus your patients will earn. Once a program has been added it will apply to all of your patients.<br>
                            *A survey score is considered perfect if all the questions get a positive response.
                            </strong>    
                        </div>
                <div class="ibox-content">
                    <?= $this->Form->create('', ['name' => 'mileProg', 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                    <?php if($vendorId != false): ?>
                       <?= $this->Form->input('', ['ng-model'=> 'request.vendor_id', 'ng-init' => "request.vendor_id = ".$vendorId."; !vmId.length ? getVendorRewards() : '' ",'label' => false, 'required' => true, 'type' => 'hidden']); ?>
                         
                    <?php else: ?>
                        <div class="form-group">
                            <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                               <?= $this->Form->select('vendor_id', [], 
                                        [
                                            'label' => false, 
                                            'required' => true,
                                            'ng-disabled' => 'readProgram', 
                                            'class' => ['form-control'],
                                            'ng-change' => 'getVendorRewards(); Miles.clearRewards();',
                                            'ng-model' => 'request.vendor_id' ,
                                            'ng-options' => 'vendor.id as vendor.org_name for vendor in vendors',
                                            
                                            'empty' => '--Select a practice name--'
                                        ]
                                    ); 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                    <?= $this->Form->label('name', __('Program Name'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'programName']);  ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('name', ['ng-model'=> 'request.name','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>     
                    </div>

                        <?= $this->Inspinia->horizontalRule(); ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="  The program type can be limited or unlimited. 

In an unlimited program you can set the number of perfect score surveys the patient must achieve to earn the milestone. The milestone will be achieved every time the patient completes the set number of perfect scores on the compliance survey. You can set the reward the patient will earn.

In a limited program you can divide the patient visits into different phases and each phase can have its own perfect survey milestone. When the first perfect score survey for Phase 1 is hit, the milestone is achieved. Once a milestone has been achieved for a phase the patient proceeds to the next phase.
                            " id='programTypeLabel' > <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Program Type</label>
                            <div class="col-sm-10">
                                <?= $this->Form->input('fixed_term', ['label' => false, 'required' => true, 'class' => ['form-control'],'ng-model' => 'request.fixed_term','options' => ['0' => 'Unlimited', '1' => 'Limited'], 'empty' => '--Please Select Type--']); ?>
                            </div>
                        </div>
                        <div ng-hide = "true" ><!-- Removed end_duration for the time being --> 
                            <?= $this->Inspinia->horizontalRule(); ?>
                            <div class="form-group">
                                <?= $this->Form->label('end_duration', __('Duration(in months)'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                <div class="col-sm-10">
                                    <?= $this->Form->input('end_duration',
                                            [
                                                'id'=>'endDuration',
                                                'onkeypress'=>"disAllowDotInIntegerInput(event);",
                                                'label' => false,
                                                'step'=>1,
                                                'ng-init' => 'request.end_duration = request.end_duration || 1',
                                                'ng-model'=> 'request.end_duration', 
                                                'min'=>0,
                                                "type"=>"number",
                                                'onpaste'=>"return false;",
                                                'class' => ['form-control'],
                                            ]
                                        ); 
                                    ?>

                              </div>
                            </div>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>

            <div class="ibox float-e-margins" ng-if = "request.fixed_term.length">
                <div class="ibox-title">
                    <h5><?= __('Phases Distribution') ?></h5>
                    <div class="text-right">
                        <button id= "submit" class="btn btn-primary" ng-click= "addNew()" ng-if = "request.fixed_term == 1">Add Another Level</button>
                    </div>
                </div>
                

                    
                  <div class="ibox-content" style="display: block;">
                    <?= $this->Form->create(null, ['data-toggle'=>'validator', 'name' => 'mileProgLevels']) ?>
                
                    <table class="table">
                        <thead>
                            <tr>
                                <th ng-if = "request.fixed_term == 1"><?= $this->Paginator->sort('Level')?></th>
                                <div class="tooltip-demo">
                                    <th class= "text-center" scope="col" id = 'levelName' data-toggle="tooltip" data-placement="top" title="Add your custom phase name here."><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Name</th>
                                </div>
                                <div class="tooltip-demo">
                                    <th class= "text-center" id = 'cumulativePerfectPatient' scope="col" data-toggle="tooltip" data-placement="top" title="Number of perfect score surveys a patient needs to achieve a milestone."><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Perfect Patient(Cumulative)</th>
                                </div>
                                <div class="tooltip-demo">
                                    <th class= "text-center" id= 'configureRewardsHeading' scope="col" data-toggle="tooltip" data-placement="top" title="Here you can set the reward value a patient will receive when they achieve a milestone. "><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Configure rewards</th>
                                </div>                    
                            </tr>
                        </thead>
                        <tbody>
                                
                                <tr ng-repeat = "level in request.milestone_levels" ng-if = "request.fixed_term == 1">
                                    
                                    <td>Level {{level.level_number = $index + 1}}</td>
                                    <td>
                                    <?= $this->Form->input("vendor_milestone_name", array(
                                                                        "label" => false,
                                                                        'id' => 'levelName', 
                                                                        'required' => true,
                                                                        "class" => "form-control",
                                                                        'ng-model' => 'level.name',
                                                                        'name' => 'vMsName{{$id}}'));
                                    ?>   
                                    </td>
                                    <td >
                                    <?= $this->Form->input("vendor_milestone_perfect_patient", array(
                                                                    "label" => false,
                                                                    'id' => 'cumulativePerfectPatient', 
                                                                    'required' => true,
                                                                    'type' => "number",
                                                                    'min' => '{{$index == 0 ? 1 : (request.milestone_levels[$index-1].milestone_level_rules[0].level_rule - 0) + 1 }}',
                                                                    'string-to-number',
                                                                    "class" => "form-control",
                                                                    'ng-model' => 'level.milestone_level_rules[0].level_rule',
                                                                    'name' => 'vMsName{{$id}}',
                                                                    'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                                    'onpaste'=>'return false;'));
                                    ?>
                                    </td>
                                    <td>
                                        <?= $this->Form->button(__('Configure Rewards'), 
                                                [
                                                    'id' => 'configureRewardsHeading', 
                                                    'data-target' => '#milestoneModal', 
                                                    'data-toggle' => 'modal',
                                                    'ng-click'=> 'Miles.reward($index)',
                                                    'ng-disabled' => '!request.vendor_id',
                                                    'type'=>'button',
                                                    'class' => ['btn', 'btn-md', 'btn-success']
                                                ]
                                            ) 
                                        ?>
                                        <i class="fa fa-lg fa-info-circle" ng-show="!request.vendor_id" title="Select a practice before you configure rewards."></i>
                                        
                                        <button ng-if="$index > 1" class="close" type="button" ng-click = "Miles.remove('milestone_levels', $index)">
                                                    <span aria-hidden="true">×</span>
                                        </button>
                                    </td>
                                    
                                </tr>
                                <tr ng-if = "request.fixed_term == 0">
                                    <td>
                                    <?= $this->Form->input("vendor_milestone_name", array(
                                                                        "label" => false,
                                                                        'id' => 'levelName', 
                                                                        'required' => true,
                                                                        "class" => "form-control",
                                                                        'ng-model' => 'request.milestone_levels[0].name',
                                                                        'name' => 'vMsName{{$id}}'));
                                    ?>   
                                    </td>
                                    <td >
                                    <?= $this->Form->input("vendor_milestone_points", array(
                                                                    "label" => false,
                                                                    'id' => 'cumulativePerfectPatient', 
                                                                    'required' => true,
                                                                    'type' => "number",
                                                                    'min' => 0,
                                                                    'string-to-number',
                                                                    "class" => "form-control",
                                                                    'ng-model' => 'request.milestone_levels[0].milestone_level_rules[0].level_rule',
                                                                    'name' => 'vMsName{{$id}}',
                                                                    'onkeypress'=>'disAllowDotInIntegerInput(event);',
                                                                    'onpaste'=>'return false;'));
                                    ?>
                                    </td>
                                    <td>
                                    <?= $this->Form->button(__('Configure Rewards'), 
                                            [
                                                'id' => 'configureRewardsHeading',
                                                'data-target' => '#milestoneModal', 
                                                'data-toggle' => 'modal',
                                                'ng-click'=> 'Miles.reward(0)',
                                                'ng-disabled' => '!request.vendor_id', 
                                                'type'=>'button',
                                                'class' => ['btn', 'btn-md', 'btn-success']
                                            ]
                                        ) 
                                    ?>
                                    &nbsp;
                                    <i class="fa fa-lg fa-info-circle" ng-show="!request.vendor_id" title="Select a practice before you configure rewards."></i>
                                        
                                        <button ng-if="$index > 1" class="close" type="button" ng-click = "Miles.remove('milestone_levels', $index)">
                                                    <span aria-hidden="true">×</span>
                                        </button>
                                    </td>
                                    
                                </tr>
                        </tbody>
                    </table>
                    <?= $this->Form->end() ?>
                    
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <p><strong>{{save_message}}</strong></p>
                        </div>
                        <br><br>
                        <div class="col-sm-4 col-sm-offset-4">    
                            <?= $this->Form->button(__('Save'), 
                                    [
                                        'id'=> 'saveMile',
                                        'type' => 'button',
                                        'class' => ['btn', 'btn-primary'], 
                                        'ng-click' => 'Miles.checkId()', 
                                        'ng-disabled'=>'mileProgLevels.$invalid || mileProg.$invalid']) ?>
                            
                            <?= $this->Form->postLink(__('Cancel'), ['action' => 'index'], ['class' => ['btn','btn-danger']]) ?>

                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Modal Window For Milestone Levels -->
    <div id="milestoneModal" class="modal inmodal fade" aria-hidden="true" role="dialog" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Configure Rewards</h4>
                    <div class="text-right">
                        <button id= "configRwrd" class="btn btn-primary" ng-click= "Miles.addNewReward()"  ng-if=" request.milestone_levels[levelIndex].milestone_level_rewards.length <=1 "
                        ng-disabled = "!request.milestone_levels[levelIndex].milestone_level_rewards[0].reward_type_id"
                        >Add Another Reward</button>
                        <button ng-click = "getVendorRewards()" class ="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button>
                    </div>

                </div>
                <div id= "modal-body"  class="modal-body">
                    <div class="ibox-content">
                        <?= $this->Form->create('', ['name'=>'configReward{{levelIndex}}', 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                            <div ng-repeat="reward in request.milestone_levels[levelIndex].milestone_level_rewards">
                                <button ng-if="$index > 0" class="close pull-right" type="button" ng-click = "Miles.removeReward($index)">
                                                <span aria-hidden="true">×</span>
                                </button>
                                <br>
                                <div class="form-group">
                                    <?= $this->Form->label('reward_type', __('Reward Type'), ['class' => ['col-sm-2', 'control-label']]) ?>
                                    <div class="col-sm-10">
                                        <?= $this->Form->select('reward_type', [], 
                                                [
                                                    'label' => false, 
                                                    'required' => true, 
                                                    'class' => ['form-control'],
                                                    'ng-model' => 'reward.reward_type_id' ,
                                                    'ng-options' => 'c.id as c.type for c in reward_types | 
                                                                    filter: $index > 0 ? 
                                                                    (request.milestone_levels[levelIndex].milestone_level_rewards[$index-1].reward_type_id == 1 ? 2 : 1)
                                                                    : ""   
                                                    ',
                                                    'ng-click' => 'Miles.checkReward($index, reward.reward_type_id)',
                                                    'empty' => '--Choose a reward type--'
                                                ]
                                            ); 
                                        ?>
                                    </div>
                                </div>
                                <?= $this->Inspinia->horizontalRule(); ?>
                                <div class="form-group" ng-if="reward.reward_type_id == 1">
                                    <?= $this->Form->label('points', __('Points'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                    <div class="col-sm-10">
                                       <?= $this->Form->input('points', 
                                                [
                                                    'ng-model' => 'reward.points',
                                                    "type" => "number",
                                                    'min' => 0,
                                                    'label' => false, 
                                                    'required' => true, 
                                                    'class' => ['form-control']
                                                ]
                                            ); 
                                        ?>
                                    </div>     
                                </div>
                                <div class="form-group" ng-if="reward.reward_type_id == 2">
                                    <div ng-if= "gift_coupons.length">
                                        <?= $this->Form->label('gift_coupon', __('Gift Coupons'), ['class' => ['col-sm-2', 'control-label']]); ?>
                                        <div class="col-sm-10">
                                           <?= $this->Form->select('gift_coupon', [], 
                                                    [
                                                        'label' => false, 
                                                        'required' => true,
                                                        'class' => ['form-control'],
                                                        'ng-model' => 'reward.reward_id' ,
                                                        'ng-options' => 'c.id as c.description for c in gift_coupons',
                                                        
                                                        'empty' => '--Choose a gift Coupon--'
                                                    ]
                                                ); 
                                            ?>
                                        </div>
                                    </div>     
                                    <div ng-if= "!gift_coupons.length">
                                        <strong class = "col-sm-offset-1">No Gift Coupons present for this Practice. <a href="{{addCoupon}}" id="newCoupon" target="_blank">Click here</a> to add coupons for this practice.</strong>
                                    </div>  
                                </div>
                                <?= $this->Inspinia->horizontalRule(); ?>
                                <?= $this->Inspinia->horizontalRule(); ?>
                            </div>

                        <?= $this->Form->end() ?>

                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <?= $this->Form->button(__('Close'), ['id'=>'close', 'class' => ['btn','btn-white']]) ?> -->
                    <?= $this->Form->button(__('Done'), 
                            [
                                'ng-disabled'=>'configReward{{levelIndex}}.$invalid', 
                                'id' => 'saveReward',
                                'type'=> 'button',
                                'data-dismiss' => 'modal',
                                'class' => ['btn', 'btn-primary'] 
                            ]
                        )
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-->
<script type="text/javascript">
<?php
    echo 'var vendorMilestoneId = "'.$vendorMilestone->id.'";';
    echo 'var vendors = '.json_encode($vendors).';';
?>
</script>

