<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Gift Coupons</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Gift Coupons', ['controller' => 'giftCoupons', 'action' => 'add', $giftCouponType= 'standard'],['class' => ['btn', 'btn-success']])?></div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> This is a list of your available gift coupons. Gift coupons be used as a ‘Dollar Amount Off Certificate’, a ‘% Discount’ or a ‘Free Service’ your patients can redeem their points for. You have the option to set an expiration date on your coupons.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Practice Name</th>
                             <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Gift coupon details." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Description</th>
                            </div>
                             <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="The amount of points required to redeem for a gift coupon." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Points</th>
                            </div>
                             <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Time (in days) until the issued gift coupon expires." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Expiry Duration</th>
                            </div>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php   $i=0;
                                foreach ($giftCoupons as $giftCoupon): 
                                    $i++;
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $giftCoupon->has('vendor') ? $giftCoupon->vendor->org_name : '' ?></td>
                            <td><?= h($giftCoupon->description) ?></td>
                            <td><?= $this->Number->format($giftCoupon->points) ?></td>
                            <td><?= $this->Number->format($giftCoupon->expiry_duration).' days' ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $giftCoupon->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $giftCoupon->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                    <a class = "btn btn-sm btn-danger fa fa-trash-o fa-fh" onclick = "confirmGiftCouponDeletion(<?= $giftCoupon->id ?>)" ></a><!-- 
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
                            <th scope="col">Description</th>
                            <th scope="col">Points</th>
                            <th scope="col">Expiry Duration</th>
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
                {extend: 'excel', title: 'Gift Coupons'},
                {extend: 'pdf', title: 'Gift Coupons'},

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