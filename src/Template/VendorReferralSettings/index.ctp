<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Referral Settings</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Setting', ['controller' => 'VendorReferralSettings', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Practice Name</th>
                            <th scope="col">Referral Level</th>
                            <div class="tooltip-demo">
                                    <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Points awarded to a patient for making a referral." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Referral Award Points</th>
                                </div>
                            <div class="tooltip-demo">
                                    <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Points awarded to the referred patient when they start treatment with your practice." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Referree Award Points</th>
                                </div>
                            <div class="tooltip-demo">
                                    <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="You can activate/deactivate referral levels. Only the active levels will be available when awarding points for referrals." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Status</th>
                                </div>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($vendorReferralSettings as $key => $vendorReferralSetting): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($vendorReferralSetting->vendor->org_name) ?></td>
                                <td><?= h($vendorReferralSetting->referral_level_name) ?></td>
                                <td><?= h($vendorReferralSetting->referrer_award_points) ?></td>
                                <td><?= h($vendorReferralSetting->referree_award_points) ?></td>
                                <td><?= h(($vendorReferralSetting->status)?'Enabled':'Disabled') ?></td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $vendorReferralSetting->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorReferralSetting->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php 
                                if($loggedInUser['role']['name'] != 'staff_manager'){
                                ?>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorReferralSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorReferralSetting->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                <?php } ?>                                 
                                </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                             <th>No.</th>
                            <th>Practice Name</th>
                            <th scope="col">Referral Level</th>
                            <th scope="col">Referral Award Points</th>
                            <th scope="col">Referree Award Points</th>
                            <th><?= $this->Paginator->sort('status') ?></th>
                           
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
                {extend: 'excel', title: 'Practice Referral Settings'},
                {extend: 'pdf', title: 'Practice Referral Settings'},

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