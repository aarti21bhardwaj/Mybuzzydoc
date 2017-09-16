<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Deposit Balances</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th >Practice Name</th>
                                <th>Balance</th>
                                <th >Date & Time</th>
                                <th >Modified</th>
                               
                            </tr>
                        </thead>
                    <tbody>
                    <?php foreach ($vendorDepositBalances as $vendorDepositBalance): ?>
                        <tr>
                            <td><?= $this->Number->format($vendorDepositBalance->id) ?></td>
                            <td><?= h($vendorDepositBalance->vendor->org_name) ?></td>
                            <td><?= $this->Number->currency($vendorDepositBalance->balance, 'USD') ?></td>
                            <td><?= h($vendorDepositBalance->created) ?></td>
                            <td><?= h($vendorDepositBalance->modified) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th scope="col">Id</th>
                        <th>Practice Name</th>
                        <th>Balance</th>
                        <th>Date & Time</th>
                        <th>Modified</th>
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
                {extend: 'excel', title: 'Practice Deposit Balance'},
                {extend: 'pdf', title: 'Practice Deposit Balance'},

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