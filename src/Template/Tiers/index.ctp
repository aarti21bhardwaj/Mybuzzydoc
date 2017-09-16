<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tiers</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Tier', ['controller' => 'Tiers', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> The tier program allows you define the unique rewards levels your patients will strive to obtain. Each tier will allow the patient to earn ‘cash back’ (based on percentages) on the amount they spend with your practice. This % cashback is given in the form of points. Gift coupons can also be added as a bonus for reaching new levels/tiers. The patient can redeem their earned points at any time for an e-gift card, in-office product or service.
                            On the tier program your patients are striving to obtain new tier levels within a one year period and can maintain that level for the next year.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th>Tier Name</th>
                                <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Minimum amount a patient needs to spend to reach a tier." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Lowerbound</th>
                                    </div>
                                <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="  Maximum amount that can be spent in a tier." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Upperbound</th>
                                    </div>
                                <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" The cashback (in points) a patient receives is based on this % multiplier and the amount they spend in your office.
                                        " data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Multiplier</th>
                                    </div>
                                <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                          <?php 
                            
                            foreach ($tiers as $key => $tier): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <td><?= h($tier->vendor->org_name) ?></td>
                                    <td class="hidden-xs"><?= h($tier->name) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($tier->lowerbound) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($tier->upperbound) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($tier->multiplier *= 100).'%' ?></td>

                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $tier->id]).' class="btn btn-xs btn-success">' ?>
                                        <i class="fa fa-eye fa-fw"></i>
                                    </a>
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $tier->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php
                                    echo  $this->Form->postLink(__(''), ['action' => 'delete', $tier->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tier->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                ?> 
                                
                                
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th>Tier Name</th>
                                <th class="hidden-xs">Lowerbound</th>
                                <th class="hidden-xs hidden-sm">Upperbound</th>
                                <th class="hidden-xs hidden-sm">Multiplier</th>
                            
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
                {extend: 'excel', title: 'Tiers'},
                {extend: 'pdf', title: 'Tiers'},

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
