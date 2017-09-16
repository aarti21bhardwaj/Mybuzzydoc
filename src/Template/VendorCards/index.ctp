<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>My Card Series</h5>
                    <div class="text-right">
                        <?=$this->Html->link('Request More Cards', ['controller' => 'VendorCardRequests', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                </div>

                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Card Series</th>
                                    <th scope="col">Total Cards</th>
                                    <th scope="col">Total Cards Issued</th>
                                    <th scope="col">Available Cards</th>

                                    <th scope="col" class="actions"><?= __('Actions') ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($myCardSeries as $key => $cardSeries): ?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <td><?= h($cardSeries->reseller_card_series->series) ?></td>
                                        <td><?= h($cardSeries->totalCards) ?></td>
                                        <td><?= h($cardSeries->issuedCards) ?></td>
                                        <td><?= h($cardSeries->availableCards) ?></td>
                                        <!-- <td><?= h($trainingVideo->embedded_source) ?></td> -->
                                        <td class="actions">
                                            <?= '<a href='.$this->Url->build(['action' => 'view', $cardSeries->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                             <th scope="col">No.</th>
                             <th scope="col">Video Title</th>
                             <!--  <th scope="col">Video Embedded Source</th> -->
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
