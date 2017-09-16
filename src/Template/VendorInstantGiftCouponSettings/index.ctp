<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Instant Gift Coupons</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Instant Gift Coupon Settings', ['controller' => 'vendorInstantGiftCouponSettings', 'action' => 'add', 1],['class' => ['btn', 'btn-success']])?></div>
                        </div>
                    </div>
                    <div class="ibox-content">
                         <div class="alert alert-success"> 
                            <strong> NOTICE: Instant Rewards will still be available on a patient's account if corresponding points are deleted.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Amount Spent</th>
                            <th scope="col">Points Earned</th>
                            <th scope="col">Threshold Time</th>
                            <th scope="col">Redemption Expiry</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php   $i=0;
                                foreach ($vendorInstantGiftCouponSettings as $vendorInstantGiftCouponSetting): 
                                    $i++;
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $vendorInstantGiftCouponSetting->has('vendor') ? $vendorInstantGiftCouponSetting->vendor->org_name : '' ?></td>
                            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->amount_spent_threshold) ?></td>
                            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->points_earned_threshold) ?></td>
                            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->threshold_time_period).' hours' ?></td>
                            <td><?= $this->Number->format($vendorInstantGiftCouponSetting->redemption_expiry).' hours' ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $vendorInstantGiftCouponSetting->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorInstantGiftCouponSetting->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                    <a class = "btn btn-sm btn-danger fa fa-trash-o fa-fh" onclick = "confirmGiftCouponDeletion(<?= $vendorInstantGiftCouponSetting->id ?>)" ></a><!-- 
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $giftCoupon->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $giftCoupon->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?> -->
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Amount Spent</th>
                            <th scope="col">Points Earned</th>
                            <th scope="col">Threshold Time</th>
                            <th scope="col">Redemption Expiry</th>
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
                {extend: 'excel', title: 'Instant Gift Coupons'},
                {extend: 'pdf', title: 'Instant Gift Coupons'},

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