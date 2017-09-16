<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Referral Tier Perks</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Referral Tier Perk', ['controller' => 'ReferralTierPerks', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                    <tr>
                                <th>No.</th>
                                <?php if($vendorId == 1): ?>
                                    <th>Practice</th>
                                <?php endif; ?>
                                <th>Referral Tier</th>
                                <th class="hidden-xs">Perk</th>
                                <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            foreach ($referralTierPerks as $key => $referralTierPerk): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <?php if($vendorId == 1): ?>
                                        <td class="hidden-xs"><?= h($referralTierPerk->referral_tier['vendor']['org_name']) ?></td>
                                    <?php endif; ?>
                                    <td class="hidden-xs"><?= h($referralTierPerk->referral_tier['name']) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($referralTierPerk->perk) ?></td>
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $referralTierPerk->id]).' class="btn btn-xs btn-success">' ?>
                                        <i class="fa fa-eye fa-fw"></i>
                                    </a>
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $referralTierPerk->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php
                                    echo  $this->Form->postLink(__(''), ['action' => 'delete', $referralTierPerk->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referralTierPerk->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                ?>    
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                                <th>No.</th>
                                <th>Referral Tier</th>
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
                {extend: 'excel', title: 'Referral Tier Perks'},
                {extend: 'pdf', title: 'Referral Tier Perks'},

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


