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
                        <h5>Referrals</h5>
                        <!-- <div class="text-right">
                         <?=$this->Html->link('Referral Form', ['controller' => 'referrals', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div> -->
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> This is a list of all the referrals coming in from your patients.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <th>Practice Name</th>
                            <th>Refer From</th>
                            <th>Refer To</th>
                            <th>Referral Name</th>
                            <th>Date & Time</th>

                            <!-- <th class="hidden-xs hidden-sm"><?= $this->Paginator->sort('status') ?></th> -->
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($referrals as $key => $referral): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($referral->vendor->org_name) ?></td>
                                <td><?= h($referral->refer_from) ?></td>
                                <td><?= h($referral->refer_to) ?></td>
                                <td><?= h($referral->first_name) ?></td>

                                <?php /*<td class="hidden-xs hidden-sm"><?= h(($referral->status)?'Enabled':'Disabled') ?></td> */?>
                                
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $referral->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php /*<?= '<a href='.$this->Url->build(['action' => 'edit', $referral->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $referral->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referral->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?> */?>
                                
                                
                                
                            </td>
                            <td><?= h($referral->created) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Practice Name</th>
                            <th>Refer From</th>
                            <th>Refer To</th>
                            <th>Referral Name</th>
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
    $(document).ready(function(){
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
                {extend: 'excel', title: 'Referrals'},
                {extend: 'pdf', title: 'Referrals'},

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