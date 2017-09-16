<?= $this->Html->script(['review_statuses']) ?>
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
<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row" ng-app="requeststatus" ng-controller="RequeststatusController as Request">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Reviews</h5>
                    </div>
                    <div class="ibox-content">
                         <div class="alert alert-success"> 
                            <strong>  Here is a list of all the ratings and reviews from your patients. You can reward them from this page for leaving reviews on your different sites (Facebook, Google+, Yelp! etc).
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                             <div class="tooltip-demo">
                                    <th class= "text-center" scope="col" data-toggle="tooltip" data-placement="top" title="Name of the staff member who requested the review." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Requested By</th>
                            </div>
                             <div class="tooltip-demo">
                                    <th class= "text-center" scope="col" data-toggle="tooltip" data-placement="top" title="Practice location for which the review was requested." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Practice Location</th>
                            </div>
                             <div class="tooltip-demo">
                                    <th class= "text-center" scope="col" data-toggle="tooltip" data-placement="top" title="The name of the patient who a reviewed your practice." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Reviewed By</th>
                            </div>
                            <th>Review</th>
                            <th>Rating</th>                            
                            <th>Date & Time</th>
                             <div class="tooltip-demo">
                                    <th class= "text-center" class="actions" scope="col" data-toggle="tooltip" data-placement="top" title="A list of all the review sites your patient left reviews on. You can verify the review and issue points by clicking on the images one by one." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Requests</th>
                            </div>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($reviewRequestStatuses as $key => $reviewRequestStatus): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($reviewRequestStatus->user_id ? $users[$reviewRequestStatus->user_id] : 'Auto Request') ?></td>
                                <td><?= h($reviewRequestStatus->vendor_location['address']) ?></td>
                                <td><?= h($reviewRequestStatus->email_address) ?></td>
                                <td >    
                                    <?= h($reviewRequestStatus['vendor_review']['review']) ?>
                                </td>
                                <td><?= h($reviewRequestStatus['vendor_review']['rating']) ?></td>
                                <td><?= h($reviewRequestStatus->created) ?></td>
                                <td class="actions">
                                
                                <?php if($reviewRequestStatus->google_plus): ?>
                                    <?= $this->Form->button(__(''), ['type'=> 'button','ng-click' => 'Request.points('.$reviewRequestStatus->id.','.__("'gplus'").')', 'ng-disabled' => 'awrdPts || reviewSettings.gplus_points == 0','name' => 'gplusBtn','ng-if' => '!gplus'.$reviewRequestStatus->id , 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-google-plus'], 'ng-attr-title' => "{{reviewSettings.gplus_points == 0 && 'Points not defined' || 'Award Points'}}"]) ?> 
                                <?php 
                                    endif; 
                                    if($reviewRequestStatus->yelp): 
                                ?>
                                    <?= '<button'.' type="button" class="btn btn-xs" ng-attr-title = "{{reviewSettings.yelp_points == 0 && '."'Points not defined'".' || '."'Award Points'".'}}" name="yelpBtn" ng-disabled = "awrdPts || reviewSettings.yelp_points == 0" ng-if = "!yelp'.$reviewRequestStatus->id.'" ng-click = "Request.points('.$reviewRequestStatus->id.','.__("'yelp'").')">' ?>
                                        <?= $this->Html->image('yelp_logo.ico',
                                                            [   
                                                                'alt' => 'yelp logo',
                                                                'class' =>'fa fa-fw fa-lg',
                                                            ]); ?>
                                
                                    <?= '</button>' ?>
                                <?php 
                                    endif; 
                                    if($reviewRequestStatus->ratemd): 
                                ?>
                                    <?= '<button'.' type="button" name="ratemdBtn" class="btn btn-xs" ng-attr-title = "{{reviewSettings.ratemd_points == 0 && '."'Points not defined'".' || '."'Award Points'".'}}" ng-disabled = "awrdPts || reviewSettings.ratemd_points == 0" ng-if = "!ratemd'.$reviewRequestStatus->id.'" ng-click = "Request.points('.$reviewRequestStatus->id.','.__("'ratemd'").')">' ?>
                                        <?= $this->Html->image('ratemd_logo.ico',
                                                                [   
                                                                    'alt' => 'ratemd logo',
                                                                    'class' =>'fa fa-fw fa-lg',
                                    ]); ?>
                                
                                    <?= '</button>' ?>
                                <?php 
                                    endif; 
                                    if($reviewRequestStatus->yahoo): 
                                ?>

                                    <?= $this->Form->button(__(''), ['type'=> 'button','ng-click' => 'Request.points('.$reviewRequestStatus->id.','.__("'yahoo'").')', 'ng-disabled' => 'awrdPts || reviewSettings.yahoo_points == 0','name' => 'yahooBtn','ng-if' => '!yahoo'.$reviewRequestStatus->id , 'class' => ['btn', 'btn-sm', 'btn-success', 'fa', 'fa-yahoo'], 'ng-attr-title' => "{{reviewSettings.yahoo_points == 0 && 'Points not defined' || 'Award Points'}}"]) ?> 
                                <?php 
                                    endif; 
                                    if($reviewRequestStatus->healthgrades): 
                                ?>
                                    <?= '<button'.' type="button" name="healthgradesBtn" class="btn btn-xs" ng-attr-title = "{{reviewSettings.healthgrades_points == 0 && '."'Points not defined'".' || '."'Award Points'".'}}" ng-disabled = "awrdPts || reviewSettings.healthgrades_points == 0" ng-if = "!healthgrades'.$reviewRequestStatus->id.'" ng-click = "Request.points('.$reviewRequestStatus->id.','.__("'healthgrades'").')">' ?>
                                        <?= $this->Html->image('healthgrades_logo.ico',
                                                            [
                                                                'alt' => 'healthgrades logo',
                                                                'class' =>'fa fa-fw fa-lg',
                                                            ]); ?>
                                
                                    <?= '</button>' ?> 
                                <?php endif;  ?> 
                                   
                                
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot >
                    <tr>
                            <th>No.</th>
                            <th>Submitted By</th>
                            <th>Practice Location</th>
                            <th>PeopleHub User Id</th>
                            <th style="word-wrap: break-word; max-width: 20px">Review</th>
                            <th>Date & Time</th>                           
                            <th>Rating</th> 
                           
                    </tr>
                    </tfoot>
                    </table>

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
                    var select = $('<select style="word-wrap: break-word; max-width: 200px"><option value=""></option><id = "init"><id/></select>')
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
                        select.append( '<option style="word-wrap: break-word; max-width: 200px" value="'+d+'">'+d+'</option>' )
                    } );
                } );
            },

            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Review Request Status'},
                {extend: 'pdf', title: 'Review Request Status'},

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








