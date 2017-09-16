<?php
/**
  * @var \App\View\AppView $this
  */
 // pr($myCardSeries);die;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Issue card requests information of series "<?= $myCardSeries->reseller_card_series->series?>"</h5>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Start Range</th>
                                    <th scope="col">End Range</th>
                                    <th scope="col">Total Cards</th>
                                    <th scope="col">Request Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($myCardSeries->vendor_card_requests as $key => $cardReq): ?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <td><?= h($cardReq->start) ?></td>
                                        <td><?= h($cardReq->end) ?></td>
                                        <td><?= $this->Number->format($cardReq->end - $cardReq->start) ?></td>
                                        <td><?= h($cardReq->created) ?></td>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                             <th scope="col">No.</th>
                                    <th scope="col">Start Range</th>
                                    <th scope="col">End Range</th>
                                    <th scope="col">Total Cards</th>
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
            {extend: 'excel', title: 'Training Videos'},
            {extend: 'pdf', title: 'Training Videos'},

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
