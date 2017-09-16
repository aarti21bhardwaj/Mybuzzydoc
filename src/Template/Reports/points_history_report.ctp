<?= $this->Html->script(['plugins/fullcalendar/moment.min']) ?>
<?= $this->Html->script(['plugins/daterangepicker/daterangepicker']) ?>
<?= $this->Html->css('plugins/daterangepicker/daterangepicker-bs3') ?>
   
<?= $this->Form->create($staffReports, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal'])?>
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

  <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Points History Report</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                       
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <!-- <th>No.</th> -->
                            <th>Staff Name</th>
                            <th>Patient Name</th>
                            <th>Status</th>
                            <th>Points</th>
                            <th>Details</th>
                            <th class ="sorting_desc">Date and Time</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($staffReports as $user):
                                $user = $user->toArray();
                                foreach ($user as $activityType => $reports) {
                                    if(is_array($user[$activityType]) && count($user[$activityType]) > 0){
                                        foreach ($reports as $report) {
                                           
                                            $i++;
                                            $transactionIdFieldName = 'peoplehub_transaction_id';
                                            if(isset($report['transaction_number'])){
                                                $transactionIdFieldName = 'transaction_number';
                                            }
                                            $peoplehubTransactionId = $report[$transactionIdFieldName];
                        ?>

                            <tr>
                                <!-- <td><?= $i ?></td> -->
                                <td><?= $user['first_name']." ".$user['last_name']?></td>
                              
                               <td>
                                <?php
                                 if($activityType == 'legacy_redemptions' || $activityType == 'Legacy Redemption') {
                                       echo $report['redeemer_name'];
                                    } elseif ($activityType == 'gift_coupon_awards') {
                                      echo  $report['redeemer_name'];;
                                    } else  {
                                       echo $patientNames[$peoplehubTransactionId];
                                    }
                                ?>
                                </td>


                                <td><?= $activityType == 'legacy_redemptions' ? $report['legacy_redemption_status']['name'] : 'Awarded' ?></td>
                               
                                <td>
                                <?php
                                 if(($activityType == 'legacy_redemptions') && ($report['legacy_reward']['points'])){
                                       echo $report['legacy_reward']['points'];
                                    } elseif (($activityType == 'legacy_redemptions') && (!$report['legacy_reward']['points'])){
                                        if(!$report['legacy_redemption_amounts'] || $report['legacy_redemption_amounts'] == null ){
                                            echo $report['legacy_reward']['amount'];
                                        }else{
                                            echo  $report['legacy_redemption_amounts'][0]['amount'];
                                        }
                                    } elseif ($activityType == 'gift_coupon_awards'){
                                      echo  $report['gift_coupon']['points'];
                                    } else  {
                                       echo (isset($report['points']))? $report['points']:'';
                                    }
                                ?>
                                </td>
                               
                               <?php $modifiedactivityType = \Cake\Utility\Inflector::singularize($activityType); ?>
                                <?php $modifiedactivityType = \Cake\Utility\Inflector::humanize($modifiedactivityType); ?>
                                <td><?= $modifiedactivityType ?></td>
                                <td><?= $report['created'] ?></td>
                            </tr>
                   
                        <?php
                                   
                                        }
                                    }
                                }

                        ?>
                           
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <!-- <th>No.</th> -->
                            <th>Staff Name</th>
                            <th>Patient Name</th>
                            <th>Status</th>
                            <th>Points</th>
                            <th>'Details</th>
                            <th>'Date and Time'</th>
                        </tr>
                    </tfoot>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
            </div>

 <!-- <td><?= $activityType == 'legacy_redemptions'? $report['redeemer_name']: $patientNames[$peoplehubTransactionId] ?></td> -->
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
            "order": [[ 5, "desc" ]],
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Staff Report'},
                {extend: 'pdf', title: 'Staff Report'},

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