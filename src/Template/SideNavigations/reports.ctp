<?= $this->Html->script(['plugins/fullcalendar/moment.min']) ?>
<?= $this->Html->script(['plugins/daterangepicker/daterangepicker']) ?>
<?= $this->Html->css('plugins/daterangepicker/daterangepicker-bs3') ?>
<?php $setting = [];
//pr($vendorSettings);die;
foreach ($vendorSettings as $key => $value) {
  if($value['setting_key']['type'] == 'boolean'){
    $setting[$value['setting_key']['name']] = $value['value'];
}
//pr($setting); die;
}
?>

<?php $plan = [];
foreach ($vendorPlanFeatures as $key => $value) {
  $plan[$value['feature']['name']] = true;
} ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl">
                <h2 class="text-navy font-bold"><strong>Reporting and Analytics</strong></h2>
                <h3 class="text-navy font-bold"><strong>Keep an eye on your program with our robust reporting features. These have been created to take the guess work out of rewards.</strong></h3>
                </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center">
                <h2 class="font-bold p-md">Points History</h2>
                <p class="m-xs">See all points that have been awarded and redeemed for a selected date range.</p><br><br><br>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                        <input class="form-control dateright" type="text" id="daterange" name="point_history" placeholder='Date Range' required="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>          
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center"> 
                <h2 class="font-bold p-md">Redemptions</h2>
                <p class="m-xs">See all rewards that have been ordered. You can select a custom date range and filter by reward type.</p><br><br>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                        <input class="form-control dateleft" type="text" id="daterange" name="redemptions" placeholder='Date Range' required="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>
                <?= $this->Form->end() ?>
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">Patient Self-Registration</h2>
                <p class="m-xs">See which patients have registered for your program on their own.</p><br><br>
                
                <?php if(isset($setting['Patient Self Sign Up']) && $setting['Patient Self Sign Up']) {?>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                        <input class="form-control dateright" type="text" id="daterange" name="patient_self_registration" placeholder='Date Range' required="required"/><br>
                    </div>
                    
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>
                <?= $this->Form->end() ?>
                <?php } else {?><br><br><br>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?><br><br><br>   
                <?php } ?>


            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">Reviews</h2>
                <p class="m-xs">See all ratings and reviews that have been left by patients for your practice. You can instantly award points for reviews from this report.</p><br><br>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                        <input class="form-control dateleft" type="text" id="daterange" name="review" placeholder='Date Range' required="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">CC Transactions</h2>
                <p class="m-xs">See the transactions that have been processed for your practice bank balance.</p><br><br>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-3', 'control-label']]); ?>
                    <div class="col-sm-9">
                        <input class="form-control dateright" type="text" id="daterange" name="c_charges" placeholder='Date Range' required="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">Practice Bank Balance</h2><br><br>
                <p class="m-xs">See how much your practice currently has available  for redemptions.</p><br><br><br>
                <?=$this->Html->link('VIEW', ['controller' => 'VendorDepositBalances', 'action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
     
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">Referrals</h2>
                <p class="m-xs">See all patients that have been referred to your practice. You can also update their lead level from this report.</p><br><br>


                <?php if(isset($setting['Referrals']) && $setting['Referrals']) {?>
                <?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Select Date Range'), ['class' => ['col-sm-2', 'control-label']]); ?>    
                    <div class="col-sm-10">
                        <input class="form-control dateright" type="text" id="daterange" name="referrals" placeholder='Date Range' required="required"/>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-2 control-label">Report Type</label>
                        <div class="col-sm-2"><label> <input type="radio" control-label" value="referrals" id="optionsRadios2" name="optionsRadios">&nbsp; &nbsp;Referral People</label></div>
                            <div class="col-sm-2"><label> <input type="radio" value="referral_leads" id="optionsRadios2" name="optionsRadios">&nbsp; &nbsp;Referral leads</span>
                        </label></label></div>
                    
                 </div>

                <div class="form-group">
                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary','btn-w-m ', 'btn-rounded']]) ?>
                </div>
                <?= $this->Form->end() ?>
                <?php } else {?><br>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?><br><br><br>   
                <?php } ?>

            </div>
            
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center" >
                <h2 class="font-bold p-md">Chart View</h2><br><br>
                <p class="m-xs">Get a graphical view of activities in your practice.</p><br><br><br>
                <?=$this->Html->link('VIEW', ['controller' => 'Reports', 'action' => 'vendor-activity-report'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .content{
        height:300px;
        font-size: 15px;
    }
    .divider{
        width:5px;
        height:auto;
        display:inline-block;
    }
</style>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
     
        $('.dateright').daterangepicker({opens: 'right'});
        $('.dateleft').daterangepicker({opens: 'left'});

        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $('#reportrange').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2020',
            dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'center',
            drops: 'up',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        });
    });
</script>





