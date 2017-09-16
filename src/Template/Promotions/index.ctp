<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Promotions</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Promotions', ['controller' => 'Promotions', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Vendor Name</th>
                            <th>Description</th>
                            <th>Points</th>
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($promotions as $key=> $promotion): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($promotion->name) ?></td>
                                <td><?= h($promotion->vendor->org_name) ?></td>
                                <td><?= h($promotion->description) ?></td>
                                <td><?= h($promotion->points) ?></td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $promotion->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php 
                                    if($loggedInUser['role']['name'] == 'admin' || $promotion->vendor_id == $loggedInUser['vendor_id']){
                                   ?>
                                   <?= '<a href='.$this->Url->build(['action' => 'edit', $promotion->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                 </a>
                                   <?= $this->Form->postLink(__(''), ['action' => 'delete', $promotion->id], [
                                            'confirm' => __('Are you sure you want to delete # {0}?', $promotion->id),
                                            'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]); ?>
                                    <?php } ?>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Vendor Name</th>
                            <th>Description</th>
                            <th>Points</th>
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
                {extend: 'excel', title: 'Promotions'},
                {extend: 'pdf', title: 'Promotions'},

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












