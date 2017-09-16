<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Vendor Templates</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Vendor Template', ['controller' => 'Templates', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Template Name</th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        foreach ($templates as $key => $template): ?>
                                <tr>
                                     <td><?= $this->Number->format($key+1) ?></td>
                                    <td><?= h($template->name) ?></td>
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'add', $template->id]).' class="btn btn-xs btn-success" id="vw'.$template->id.'">' ?>
                                        <i class="fa fa-gears fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $template->id], ['confirm' => __('Are you sure you want to delete # {0}?', $template->name),'id'=>"del".$template->id, 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>              
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th>Template Name</th>
                           
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
                {extend: 'excel', title: 'Vendor Templates'},
                {extend: 'pdf', title: 'Vendor Templates'},

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