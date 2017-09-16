<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Milestones</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Milestone', ['controller' => 'VendorMilestones', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Fixed Term</th>
                            <th scope="col">End Duration</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                          <?php foreach ($vendorMilestones as $key=>$vendorMilestone): ?>
                          <tr>
                            <td><?= $this->Number->format($key+1) ?></td>
                            <td><?= h($vendorMilestone->name) ?></td>
                            <td><?= $vendorMilestone->has('vendor') ? $this->Html->link($vendorMilestone->vendor->org_name, ['controller' => 'Vendors', 'action' => 'view', $vendorMilestone->vendor->id]) : '' ?></td>
                            <td><?= h($vendorMilestone->fixed_term) ?></td>
                            <td><?= $this->Number->format($vendorMilestone->end_duration) ?></td>
                            <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'add', $vendorMilestone->id]).' class="btn btn-xs btn-success" id="vw'.$vendorMilestone->id.'">' ?>
                                        <i class="fa fa-gears fa-fw"></i>
                                        </a>
                                        <!-- <button class = "btn btn-sm btn-danger fa fa-trash-o fa-fh" onclick="deleteProgram()">
                                        </button> -->
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorMilestone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorMilestone->name),'id'=>"del".$vendorMilestone->id, 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>             
                            </td>
                            </tr>
                        <?php endforeach; ?>    
                    </tbody>
                    <tfoot>
                    <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Fixed Term</th>
                            <th scope="col">End Duration</th>
                        
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
    
    function deleteProgram(name){

        swal("Error in deleting Program", "This program cannot be deleted at the moment", "error");
    }
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
                {extend: 'excel', title: 'Milestones'},
                {extend: 'pdf', title: 'Milestones'},

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