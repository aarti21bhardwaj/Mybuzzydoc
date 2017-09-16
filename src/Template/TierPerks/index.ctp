<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tier Perks</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Tier Perk', ['controller' => 'TierPerks', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                         <div class="alert alert-success"> 
                            <strong> Perks are privileges or bonuses granted to your patients in addition to the points they earn at each tier level. You can add multiple perks for a tier level.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                    <tr>
                                <th>No.</th>
                                <?php if($vendorId == 1): ?>
                                    <th>Practice</th>
                                <?php endif; ?>
                                <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Tier level which the patient must achieve to receive the added perk." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Tier</th>
                                    </div>
                                <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Privileges/bonus issued to your patients. The perks you add here will be shown on the patient’s account when they achieve the respective tier level." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Perk</th>
                                    </div>
                                <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            foreach ($tierPerks as $key => $tierPerk): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <?php if($vendorId == 1): ?>
                                        <td class="hidden-xs"><?= h($tierPerk->tier['vendor']['org_name']) ?></td>
                                    <?php endif; ?>
                                    <td class="hidden-xs"><?= h($tierPerk->tier['name']) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($tierPerk->perk) ?></td>
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $tierPerk->id]).' class="btn btn-xs btn-success">' ?>
                                        <i class="fa fa-eye fa-fw"></i>
                                    </a>
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $tierPerk->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php
                                    echo  $this->Form->postLink(__(''), ['action' => 'delete', $tierPerk->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tierPerk->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                ?>    
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                                <th>No.</th>
                                <th>Tier</th>
                                <th class="hidden-xs">Perk</th>
                           
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
                {extend: 'excel', title: 'Tier Perks'},
                {extend: 'pdf', title: 'Tier Perks'},

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


