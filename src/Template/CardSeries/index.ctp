<?php
// pr($cardSeries);die;
/**
  * @var \App\View\AppView $this
  */
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Card Series</h5>
                </div>

                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Series</th>
                                    <th scope="col">Practice Name</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Modified</th>
                                    <th scope="col">Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($cardSeries['response'] as $key => $series): ?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <!-- <td><?= $this->Number->format($trainingVideo->id) ?></td> -->
                                        <td><?= h($series->series) ?></td>
                                        <td><?= (!empty($series->vendor_id))?h($series->vendor->name):''; ?></td>
                                        <td><?= h($series->created) ?></td>
                                        <td><?= h($series->modified) ?></td>
                                        <td><?= h($series->status ? 'Enabled' : 'Disabled') ?></td>
                               </tr>
                           <?php endforeach; ?>
                       </tbody>
                       <tfoot>
                        <tr>
                          <th scope="col">No.</th>
                          <th scope="col">Series</th>
                          <th scope="col">Practice Name</th>
                          <th scope="col">Created</th>
                          <th scope="col">Modified</th>
                          <th scope="col">Status</th>
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
            {extend: 'excel', title: 'Card Series'},
            {extend: 'pdf', title: 'Card series'},

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
