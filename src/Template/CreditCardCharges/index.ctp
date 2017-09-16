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
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= __('Credit Card Charges') ?></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Practice Name</th>
                            <th>Transcation Id</th>
                            <th scope="col">Response Code</th>
                            <th>Amount</th>
                            <th>Transaction Fees</th>
                            <th>Date & Time</th>
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($creditCardCharges as $creditCardCharge): ?>
                        <tr>
                            <td><?= $this->Number->format($creditCardCharge->id) ?></td>
                            <td><?= h($creditCardCharge->vendor->org_name) ?></td>
                            <td><?= h($creditCardCharge->transactionid) ?></td>
                            <td>
                            <?php
                             if($creditCardCharge->response_code == 1){
                                    echo 'Success -'.($creditCardCharge->response_code);
                                } else{
                                    echo 'Failed -'.($creditCardCharge->response_code);

                                }?>

                            </td>
                            <td><?= $this->Number->currency($creditCardCharge->amount, 'USD') ?></td>
                            <td><?= $this->Number->currency($creditCardCharge->transaction_fee, 'USD') ?></td>
                            <td><?= h($creditCardCharge->created) ?></td>
                            <td>
                            <?= '<a href='.$this->Url->build(['action' => 'view', $creditCardCharge->id]).' class="btn btn-xs btn-success">' ?>
                                <i class="fa fa-eye fa-fw"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                    <tfoot>
                    <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Practice Name</th>
                            <th>Transcation Id</th>
                            <th scope="col">Response Code</th>
                            <th>Amount</th>
                            <th>Transaction Fees</th>
                            <th>Date & Time</th>
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
                    var select = $('<select><option value=""></option></select>')
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
                {extend: 'excel', title: 'Credit Card Charges'},
                {extend: 'pdf', title: 'Credit Card Charges'},

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