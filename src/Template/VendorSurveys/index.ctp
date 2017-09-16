<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Surveys</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Survey', ['controller' => 'VendorSurveys', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th class="hidden-xs">Survey Name</th>
                                <th>Created</th>
                                <th>Modified</th>
                                <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                            <?php foreach ($vendorSurveys as $key =>  $vendorSurvey): ?>
                            <tr>
                            <td><?= $this->Number->format($key+1) ?></td>
                            <td><?= h($vendorSurvey->vendor->org_name) ?></td>
                            <td><?= H($vendorSurvey->survey->name) ?></td>
                            <td><?= h($vendorSurvey->created) ?></td>
                            <td><?= h($vendorSurvey->modified) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $vendorSurvey->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorSurvey->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorSurvey->id], [
                                'confirm' => __('Are you sure you want to delete # {0}?', $vendorSurvey->id),
                                'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th>Survey Name</th>
                                <th>Created</th>
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
                {extend: 'excel', title: 'Practice Survey'},
                {extend: 'pdf', title: 'Practice Survey'},

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


