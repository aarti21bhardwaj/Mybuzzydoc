<?= $this->Html->script(['plugins/fullcalendar/moment.min']) ?>
<?= $this->Html->script(['plugins/daterangepicker/daterangepicker']) ?>
<?= $this->Html->css('plugins/daterangepicker/daterangepicker-bs3') ?>

<?= $this->Form->create(null, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
<div class="col-sm-3">
    <div class="form-group">
        <input class="form-control" type="text" name="daterange" placeholder='Date Range' required="required"/>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
    </div>
</div>
<?= $this->Form->end() ?>
<?= $this->Html->script(['referral_leads']) ?>
<div class="wrapper wrapper-content animated fadeInRight" ng-app="referralLeads" ng-controller="ReferralLeadsController as ReferralLead">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Referral Leads</h5>
                    </div>
                    <div class="ibox-content">
                         <div class="alert alert-success"> 
                            <strong> This is a list of the prospective patient leads. When a referred patient falls through or moves into treatment, you will need to update the status of the referral. The points will be issued automatically when the referral level is updated according to your Practice Referral Settings.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <!-- <th>Vendor Name</th> -->
                            <th>Referrer</th>
                            <th>Referee</th>
                            <th>Referee Phone</th>
                            <th>Referee Email</th>
                            <?php if($loggedInUser['vendor_id'] != 1):?>
                                <th>Referral Level</th>
                            <?php endif; ?>
                            <th>Status</th>
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($referralLeads as $key => $referralLead): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <!-- <td><?= h($referralLead->vendor->org_name) ?></td> -->
                                <td><?= h($referralLead->referral->refer_from) ?></td>
                                <td><?= h($referralLead->first_name." ".$referralLead->last_name) ?></td>
                                <td><?= h($referralLead->phone ? $referralLead->phone : "No phone is set") ?></td>
                                <td><?= h($referralLead->email ? $referralLead->email : "No Email is set") ?></td>
                                <!-- <td  class="hidden-sm hidden-xs">
                                <?= $referralLead->has('referral') ? $this->Html->link($referralLead->referral->id, ['controller' => 'Referrals', 'action' => 'view', $referralLead->referral->id]) : '' ?> (PID)
                                </td> -->
                                
                                <?php if($loggedInUser['vendor_id'] != 1):?>
                                    <td id="level<?=$referralLead->id?>">
                                        <?php $vendorId = $referralLead->vendor_id?>
                                        <!--Select Level will come here -->
                                        <select 
                                            ng-options="lev.id as lev.referral_level_name for lev in referralLevels[<?= $vendorId ?>] | filter:lev.status != 0" 
                                            ng-if="referralLevels[<?=$vendorId ?>].length > 0 && <?= $referralLead->referral_status_id?> != 2"
                                            ng-model="referralSettings[<?= $referralLead->id?>]" 
                                            ng-disabled="awardReferralButton"
                                            ng-change="ReferralLead.getNewPatientId(<?= $vendorId ?>, <?= $referralLead->id?>, <?=$referralLead->referral->peoplehub_identifier ?>)"
                                        >

                                            <option value="">
                                                --Select Level--
                                            </option>
                                        </select>

                                        <div ng-if="referralLevels[<?=$vendorId?>].length == 0 && <?= $referralLead->referral_status_id?> != 2">
                                            No referral levels are set
                                        </div>
                                        <div ng-if="<?= $referralLead->referral_status_id?> == 2">
                                            <?php if(isset($referralLead->vendor_referral_setting) && $referralLead->vendor_referral_setting != null): ?>
                                                <?= $referralLead->vendor_referral_setting->referral_level_name  ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <td id="status<?=$referralLead->id?>">
                                    <?= h(($referralLead->referral_status->status)) ?>
                                </td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $referralLead->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php if($referralLead->referral_status->status != 'Complete') { ?>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $referralLead->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php } ?>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $referralLead->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referralLead->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                             
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <!-- <th>Vendor Name</th> -->
                            <th>Referrer</th>
                            <th>Referee</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Referral Level</th>
                            <th>Status</th>
                           
                    </tr>
                    </tfoot>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
<!-- Modal starts Here-->
    <div class="modal inmodal fade" id="referralModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">Search Patient</h4>
                    <small class="font-bold">Search for the referred patient.</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form name="searchForm" ng-submit="ReferralLead.search()" class = "form-inline">
                            <div class="search-input">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
    
                                    </div>
                                    <div class="panel-body text-center">
                                        <div class="input-group">
                                            <input type="text" id="search-patient-input" class="form-control input col-lg-10" placeholder="Enter phone number or email or card number" ng-required ng-model="searchQuery">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn  btn-success">
                                                    Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div ng-show="showSearchCount" class="alert alert-success">
                                {{searchResults.length}} results found
                            </div>
                            <table class="table table-hover table-mail">
                                <tbody>
                                    <tr ng-repeat="user in searchResults" ng-click="ReferralLead.awardReferralPoints(user.id)" data-dismiss="modal">
                                        <td class="mail-ontact">{{user.name}}
                                        <span class="label-primary pull-right" >Registered</span></td>

                                        <td class="mail-subject">{{ user.email || user.guardian_email}}</td>
                                        <td class="">{{user.phone}}</td>
                                    <!-- <td class="text-right mail-date">{{SearchCtrl.cards(user.user_card)}}</td> -->
                                    </tr>
                                </tbody>
                            </table>         
        <!-- /.col-lg-6 -->
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
         $('input[name="daterange"]').daterangepicker();

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
                opens: 'right',
                drops: 'down',
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
        $('.dataTables').DataTable({
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option><id = "init"><id/></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            },

            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Referral Leads'},
                {extend: 'pdf', title: 'Referral Leads'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                  }
                }
            ],

        });

    });
</script>
<script type="text/javascript">
/* this function Updates the user for there level */
/*    $('select[referral_lead_id]').change(function(){
        var referral_lead_id = $(this).attr('referral_lead_id');
        var value = $(this).val();
        updateTemplateData(referral_lead_id,value);
    });

function updateTemplateData(referral_lead_id, value){
    var url = '<?php echo $this->request->webroot; ?>';
    alert('hii');
    jQuery.ajax({
        url: url+'api/referral-leads/'+referral_lead_id,
        headers:{"accept":"application/json"},
        dataType: 'json',
            data:{
                    "vendor_referral_settings_id" : value
                },
            type: 'put',
            success:function(data){
            updateTemplateDataVal(data);
            },
    });
    
}

function updateTemplateDataVal(res){
    if(res.response.data)
        console.log(res);
}
*/
</script>