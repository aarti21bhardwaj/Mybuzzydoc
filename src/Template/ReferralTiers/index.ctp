<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Referral Tiers</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Referral Tier', ['controller' => 'ReferralTiers', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th>Referral Tier Name</th>
                                <th class="hidden-xs">Referrals Required</th>
                                <th class="hidden-xs hidden-sm">Points</th>
                                <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                          <?php 
                            
                            foreach ($referralTiers as $key => $referralTier): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <td><?= h($referralTier->vendor->org_name) ?></td>
                                    <td class="hidden-xs"><?= h($referralTier->name) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($referralTier->referrals_required) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($referralTier->points) ?></td>

                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $referralTier->id]).' class="btn btn-xs btn-success">' ?>
                                        <i class="fa fa-eye fa-fw"></i>
                                    </a>
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $referralTier->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php
                                    echo  $this->Form->postLink(__(''), ['action' => 'delete', $referralTier->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referralTier->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                ?> 
                                
                                
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                                <th>No.</th>
                                <th>Practice Name</th>
                                <th>Referral Tier Name</th>
                                <th class="hidden-xs">Referrals Required</th>
                                <th class="hidden-xs hidden-sm">Points</th>
                            
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
                {extend: 'excel', title: 'Referral Tiers'},
                {extend: 'pdf', title: 'Referral Tiers'},

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