<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="col-sm-7">
                    <h5><?= __('Redemptions') ?></h5>
                </div>
                <?php  
                    if(count($legacyRedemptions))  {
                    $attributes = ['id' => 'redemption_status_bulk_update'];
                ?>
                <div class="col-sm-5" style="margin-top:-9px;">
                    <div class="pull-right">
                    <span  id="bulk-status">
                        <?= $this->Form->select('bulk_update_status', $redemptionStatuses, $attributes); ?> 
                        <span>
                            <?= $this->Form->button('Apply', ['class' => 'btn btn-primary', 'id' => 'bulk_update_button']); ?>
                        </span>
                    </span>
                    <span>
                        <span>
                            <?= $this->Form->button('Order from Amazon', ['class' => 'btn btn-primary', 'id' => 'amazon_place_order']); ?>
                        </span>
                    </span>
                    </div>
                </div>
                <?php } ?>
            
            </div>
            <div class="ibox-content" style="display: block;">
            <div class="table-responsive">
            <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                <thead>
                    <tr>
                        <th><?= $this->Form->checkbox("selected_redemptions_select_all") ?></th>
                        <th class="select-filter">No.</th>
                        <th class="select-filter">Practice Name</th>
                        <th class="select-filter">Redeemer Name</th>
                        <th class="select-filter">Legacy Reward</th>
                        <th class="select-filter">Redemption Amount($)</th>
                        <th class="select-filter">Equivalent BD Points</th>
                        <th class="select-filter">Redemption Date</th>
                        <th>Status</th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($legacyRedemptions as $key=>$legacyRedemption): ?>
                    <tr>
                        <td><?= $this->Form->checkbox("selected_redemptions", ['redemption-id' => $legacyRedemption->id, 'amazon-id' => $legacyRedemption->legacy_reward->amazon_id, 'class' => '']) ?></td>
                        <td><?= $this->Number->format($key+1) ?></td>
                        <td><?= h($legacyRedemption->vendor->org_name) ?></td>
                        <td><?= h($legacyRedemption->redeemer_name) ?></td>
                        <td><?= h($legacyRedemption->legacy_reward->name) ?></td>
                        <?php 
                        if(isset($legacyRedemption->legacy_redemption_amounts[0]->amount)){

                                $amount = $legacyRedemption->legacy_redemption_amounts[0]->amount;
                                $points = $amount * $pointsValue;
                        }else{


                            if($legacyRedemption->legacy_reward->points){

                                    $amount = $legacyRedemption->legacy_reward->points / $pointsValue;
                                    $points = $legacyRedemption->legacy_reward->points;
                            }else{
                                $amount = $legacyRedemption->legacy_reward->amount;
                                $points = $legacyRedemption->legacy_reward->amount * $pointsValue;
                            } 
                        }
                        ?>
                        <td><?= $amount ?></td>
                        <td><?= $points ?></td>
                        <td><?= date("m/d/Y", strtotime($legacyRedemption->created));?></td>
                        <td>
                        <?php $attributes = ['value'=>$legacyRedemption->legacy_redemption_status->id,
                        'redemption_id' => $legacyRedemption->id] ;?>
                        <?= $this->Form->select('status', $redemptionStatuses, $attributes); ?>  

                        </td>
                        <td class="actions">
                            <?= '<a href='.$this->Url->build(['controller' => 'LegacyRedemptions','action' => 'edit', $legacyRedemption->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">No.</th>
                            <th scope="col">Practice Name</th>
                            <th>Redeemer Name</th>
                            <th scope="col">Legacy Reward</th>
                            <th>Redemption Amount</th>
                            <th>Equivalent BD Points</th>
                            <th>Redemption Date</th>
                            <th></th>
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
                    var select = $('<select><option value=""></option></select>')
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
                {extend: 'excel', title: 'Redemptions'},
                {extend: 'pdf', title: 'Redemptions'},

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