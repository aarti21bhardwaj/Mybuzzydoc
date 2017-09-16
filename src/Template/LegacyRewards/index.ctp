<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Rewards</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Reward', ['controller' => 'legacyRewards', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> This is a list of the rewards that are available for your patients to redeem their points for. It can range from in-house products and services to e-gift cards from one of our partners, Amazon or Tango.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Product or Service Name (visible on dashboard)." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Name</th>
                            </div>
                            <th>Practice Name</th>
                            <th>Reward Category</th>
                            <th>Product Type</th>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="The amount of points each item costs the patient to redeem (50pts = $1). Points are set only for in house product type." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Points</th>
                            </div>
                           <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" E-gift card (Amazon/Tango) redemption amount." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Amount</th>
                            </div>
                            
                            <?php if($loggedInUser['role_id'] != 1){?>
                            <th>Published</th>
                            <?php } ?>
                            
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($legacyRewards as $key=>$legacyReward):?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($legacyReward->name) ?></td>
                                <td><?= h($legacyReward->vendor->org_name) ?></td>
                                <td><?= h($legacyReward->reward_category->name) ?></td>
                                <td><?= h($legacyReward->product_type->name) ?></td>
                                <td><?= h($legacyReward->points ? $legacyReward->points : "-") ?></td> 
                                <td><?= h($legacyReward->amount ? $legacyReward->amount : "-") ?></td>
                                <?php 

                                if($loggedInUser['role_id'] != 1){
                                $checked = '';
                                if(isset($vendorlegacyReward[$legacyReward->id]) && $vendorlegacyReward[$legacyReward->id]['status']){
                                    $checked = 'checked';
                                }
                                ?> 
                                <td><?=$this->Form->checkbox('reward_id', ['vendor-legacy-reward-id' => (isset($vendorlegacyReward[$legacyReward->id]))? $vendorlegacyReward[$legacyReward->id]->id:'' ,'reward-id' => $legacyReward->id, 'checked' => $checked, 'id'=>$legacyReward->id]) ?> </td>                          
                                <?php } ?>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $legacyReward->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php 
                                    if($loggedInUser['role']['name'] == 'admin' || $legacyReward->vendor_id == $loggedInUser['vendor_id']){
                                ?>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $legacyReward->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                            <?php 
                                if($loggedInUser['role']['name'] != 'staff_manager'){
                                ?>
                                <?= $this->Form->postLink(__(''), ['controller'=>'LegacyRewards','action' => 'delete', $legacyReward->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $legacyReward->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]); ?>
                            <?php } ?>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Practice Name</th>
                            <th>Reward Category</th>
                            <th>Product Type</th>
                            <th>Points</th>
                            <th>Amounts</th>
                           
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
                {extend: 'excel', title: 'Reward'},
                {extend: 'pdf', title: 'Reward'},

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