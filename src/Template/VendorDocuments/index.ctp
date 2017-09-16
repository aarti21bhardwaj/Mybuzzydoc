<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Documents</h5>
                    <div class="text-right list-inline">
                        <div>
                            <?=$this->Html->link('Add New Document', ['controller' => 'VendorDocuments', 'action' => 'add'],['id'=> 'newDocument','class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                     <div class="alert alert-success"> 
                            <strong> Documents are the best way to let your patients know more about your practice, rewards program or any other information you want to give out. The documents you add here will show up on the patient portal as well.
                            </strong>    
                        </div>
                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>

                                    <th>No.</th>
                                    <?php if(!$topHeader):?>
                                        <th>Practice Name</th>
                                    <?php endif; ?>
                                    <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" The document name you add here will show up on the patient portal for your patients to view." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Document Name</th>
                                    </div>
                                    <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Add description of your document here." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Description</th>
                                    </div>
                                    <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="  The file extensions that can be added here are PDF, DOCX, JPG, PNG and JPEG." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Filename</th>
                                    </div>
                                    <th><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vendorDocuments as $key=>$vendorDocument):?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <?php if(!$topHeader):?>
                                            <td><?= h($vendorDocument->vendor['org_name']) ?></td>
                                        <?php endif; ?>
                                        <td><?= h($vendorDocument->name) ?></td>
                                        <td><?= h($vendorDocument->description) ?></td>
                                        <td><?= h($vendorDocument->filename) ?></td>
                                        <td class="actions">
                                            <a href= "<?= $vendorDocument->document_url ?>" target="_blank" class="btn btn-xs btn-success" >
                                            <i class="fa fa-eye fa-fw"></i>
                                            </a>
                                            <!-- <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorDocument->id]).' class="btn btn-xs btn-warning"">' ?>
                                            <i class="fa fa-pencil fa-fw"></i>
                                            </a> -->
                                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorDocument->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorDocument->id),'id'=>"del".$vendorDocument->id, 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">No.</th>
                                    <?php if(!$topHeader):?>
                                        <th scope="col">Practice Name</th>
                                    <?php endif; ?>
                                    <th scope="col">Document Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Filename</th>
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
                {extend: 'excel', title: 'Documents'},
                {extend: 'pdf', title: 'Documents'},

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