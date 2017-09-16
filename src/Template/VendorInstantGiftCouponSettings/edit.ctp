<?= $this->Html->script(['tours/instantReward-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = "addInstantGiftCoupon"><?= __('Edit Instant Rewards Settings') ?></h5>
                <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="alert alert-success"> 
                    <strong> NOTICE: Instant Rewards will still be available on a patient's account if corresponding points are deleted.
                    </strong>    
                </div>
                <?= $this->Form->create($vendorInstantGiftCouponSetting, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
               <?php if($loggedInUser['role']->name == 'admin'){ ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('name', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('vendor_id', ['label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                        </div>
                    </div>
                <?php } ?>


                <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="ibox-title">
                        <h7><?= __('Set Threshold') ?></h7>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" id = "amtSpent">
                            Amount Spent
                            <br>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('amount_spent_threshold', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>1,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="col-sm-2 control-label" id = "pointsEarned">
                            Points Earned
                            <br>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('points_earned_threshold', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>



                    <?= $this->Inspinia->horizontalRule(); ?>
                     <div class="ibox-title">
                        <h7><?= __('Set Expiry') ?></h7>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" id = "timePeriodForAchievingThreshold">
                            Time period for achieving threshold
                            <br>
                            <small class="text-navy">(In number of hours)</small>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('threshold_time_period', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>1,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="col-sm-2 control-label" id = "instantRewardsAvailableForRedemption">
                            Expiry after redemption period
                            <br>
                            <small class="text-navy">(In number of hours)</small>
                        </label>
                        <div class="col-sm-4">
                            <?= $this->Form->input('redemption_expiry', ['label' => false,'onkeypress'=>"disAllowDotInIntegerInput(event);",'step'=>1, 'min'=>0,"type"=>"number",'onpaste'=>"return false;", 'required' => true,'class' => ['form-control']]); ?>
                        </div>
                    </div>

                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>'submit']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Gift Coupons</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Gift Coupons', ['controller' => 'giftCoupons', 'action' => 'add', $giftCouponType= 'instant'],['class' => ['btn', 'btn-success']])?></div>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Points</th>
                            <th scope="col">Expiry Duration</th>
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
                                <?= '<a href='.$this->Url->build(['controller' => 'GiftCoupons', 'action' => 'view', $giftCoupon->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['controller' => 'GiftCoupons', 'action' => 'edit', $giftCoupon->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                    <a class = "btn btn-sm btn-danger fa fa-trash-o fa-fh" onclick = "confirmDelOfInstantGc(<?= $giftCoupon->id ?>)" ></a><!-- 
                                <?= $this->Form->postLink(__(''), ['controller' => 'GiftCoupons', 'action' => 'delete', $giftCoupon->id], [
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