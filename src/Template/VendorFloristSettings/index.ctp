<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Vendor Flowers Settings</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Flower Setting', ['controller' => 'VendorFloristSettings', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success">
                            
                            <i class="fa fa-lg fa-info-circle"></i><strong> Default emails are already configured. Configure custom emails here.<strong>
                                
                        </div>

                        <div class="table-responsive">
                        
                            <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th scope="col">Practice Name</th>
                                    <th scope="col">Flowers</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Address</th>
                                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vendorFloristSettings as $key => $vendorFloristSetting): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <td><?= h($vendorFloristSetting->vendor->org_name) ?></td>
                                    <td><?= h($vendorFloristSetting->product_id) ?></td>
                                    <td><?= h($vendorFloristSetting->message) ?></td>
                                    <td><?= h($address->address)?></td>
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $vendorFloristSetting->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorFloristSetting->id]).' class="btn btn-xs btn-warning"">' ?>
                                        <i class="fa fa-pencil fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorFloristSetting->id], [
                                            'confirm' => __('Are you sure you want to delete # {0}?', $vendorFloristSetting->id),
                                            'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?> 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th scope="col">Practice Name</th>
                                    <th scope="col">Flowers</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Address</th>
                                   
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
                {extend: 'excel', title: 'Practice Florist Settings'},
                {extend: 'pdf', title: 'Practice Florist Settings'},

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