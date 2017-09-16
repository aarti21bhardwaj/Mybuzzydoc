<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Register New Practice', ['controller' => 'vendors', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th class="select-filter">No.</th>
                            <th >Practice Logo</th>
                            <th class="select-filter">Org Name</th>
                            <th class="select-filter">People Hub Identifier</th>
                            <th class="select-filter">Status</th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($vendors as $key=> $vendor): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><a href="<?= $vendor->image_url ?>" target="_blank"><?= $this->Html->image($vendor->image_url, array('height' => 100, 'width' => 100))?></a></td>
                                <td><?= h($vendor->org_name) ?></td>
                                <td><?= h($vendor->people_hub_identifier) ?></td>
                                <td><?= h(($vendor->status)?'Enabled':'Disabled') ?></td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $vendor->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendor->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendor->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $vendor->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?> 
                                <?= '<a href='.$this->Url->build(['controller'=> 'VendorSettings','action' => 'add', $vendor->id]).' class="btn btn-xs btn-primary">' ?>
                                <i class="fa fa-gears fa-fw"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th></th>
                            <th>Org Name</th>
                            <th>People Hub Identifier</th>
                            <th>Status</th>
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
                this.api().columns('.select-filter').every( function () {
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
                {extend: 'excel', title: 'Practices'},
                {extend: 'pdf', title: 'Vendors'},

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