<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <?= $this->Form->create($vendorPromotions, [ 'data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                         <?php if($loggedInUser['role']->name == 'admin'){ ?>
                            
                                <div class="col-sm-3">
                                   <?= $this->Form->input('vendor_id', ['empty' => '--Select Practice--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                                
                                </div>
                                    <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                               
                           
                            <?= $this->Inspinia->horizontalRule(); ?>
                            <?php } else {?>
                            <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                         <?php } ?>
                         <?= $this->Form->end() ?>

                        <div class="text-right">
                        <?=$this->Html->link('Add Promotions', ['controller' => 'Promotions', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">
                    <div class="alert alert-success"> 
                            <strong>View your list of practice promotions or ‘Ways to Earn’. Default promotions have been prefilled for your convenience. You can edit or delete the default promotions and add your own custom promotions. Promotions marked as active will be seen on the patient’s account.
                            </strong>    
                    </div>

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                    
                            <th class="select-filter">No.</th>
                            <th class="select-filter">Practice Name</th>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Display title for the way a patient can earn points." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Promotion Name</th>
                            </div>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Check this box to activate promotions on the staff dashboard." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Published</th>
                            </div>
                             <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Point value awarded to patient for the promotion." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Points</th>
                            </div>
                             <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Frequency is the number of days after which the promotion will be available on a patient’s account again after it has been recorded. Example: A 'Dental Check Up' can only be awarded twice a year; the frequency should be to 180 days." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Frequency</th>
                            </div>
                            <th class="select-filter" scope="col" class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($vendorPromotions as $key => $vendorPromotion): 
                                $checked='';
                        ?>

                        <?php if($vendorId!=1 && $loggedInUser['role']['name'] == 'admin'){ ?>
                        <tr>
                            <td><?= $this->Number->format($key+1) ?></td>
                            <td><?php  echo $vendorPromotion->vendor->org_name;?></td>
                            <td><?php  echo $vendorPromotion->name;?></td>
                            <?php 
                            $points = $vendorPromotion->points;
                            $frequency = $vendorPromotion->frequency;
                                if(isset($vendorPromotion->vendor_promotions) && (!empty($vendorPromotion->vendor_promotions))){
                                           
                                    $checked = 'checked';
                                    $points = $vendorPromotion->vendor_promotions[0]->points;
                                    $frequency = $vendorPromotion->vendor_promotions[0]->frequency;
                                }
                            ?>
                            <td>
                                <!-- <?= $this->Form->checkbox("published",['data-vendor-promotion-id'=> (isset($vendorPromotion->id))?$vendorPromotion->id:'','data-vendor-id'=> $loggedInUser['vendor_id'],'data-promotion-id'=> $vendorPromotion->id, 'data-points' => $points, 'checked'=>$checked, 'data-frequency'=>$frequency]) ?> -->
                                <?= $this->Form->checkbox("published",['data-vendor-promotion-id'=> (isset($vendorPromotion->vendor_promotions[0]->id))?$vendorPromotion->vendor_promotions[0]->id:'','data-vendor-id'=> $vendorId,'data-promotion-id'=> $vendorPromotion->id, 'data-points' => $points, 'checked'=>$checked, 'data-frequency'=>$frequency]) ?>
                            </td>
                            <td><?= $this->Number->format($points) ?></td>
                            <td><?= $this->Number->format($frequency) ?></td>
                            <td>
                                <?= '<a href='.$this->Url->build(['controller'=>'Promotions','action' => 'view', $vendorPromotion->id]).' class="btn btn-xs btn-success">' ?>
                                <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php 
                                if(isset($vendorPromotion->vendor_promotions) && (!empty($vendorPromotion->vendor_promotions))){   
                                ?>
                                <span class = "action">
                                <!-- <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorPromotion->id]).' class="btn btn-xs btn-warning edit">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a> --> 
                                 <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorPromotion->vendor_promotions[0]->id]).' class="btn btn-xs btn-warning edit">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>                                </span>
                                <?php }else{ 
                                ?>
                                <span class = "action">
                                <?= $this->Html->link('<i class="fa fa-pencil fa-fw"></i>', ['action' => 'edit', $vendorPromotion->id],['escape' => false, 'class' => 'btn btn-xs btn-warning edit', 'style'=>'display:none; ']) ?>
                                </span>
                                <?php    } ?>
                                <?php 
                                if($loggedInUser['role']['name'] == 'admin' || $vendorPromotion->vendor_id == $loggedInUser['vendor_id']){
                                ?>
                                <?= $this->Form->postLink(__(''), ['controller'=>'VendorPromotions','action' => 'delete', $vendorPromotion->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $vendorPromotion->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]); ?>
                            <?php } ?>
                            </td>
                        </tr>




                        <?php } else {?>
                         <tr>
                            <td><?= $this->Number->format($key+1) ?></td>
                            <td><?php  echo $vendorPromotion->vendor->org_name;?></td>
                            <td><?php  echo $vendorPromotion->name;?></td>
                            <?php 
                            $points = $vendorPromotion->points;
                            $frequency = $vendorPromotion->frequency;
                                if(isset($vendorPromotion->vendor_promotions) && (!empty($vendorPromotion->vendor_promotions))){
                                           
                                    $checked = 'checked';
                                    $points = $vendorPromotion->vendor_promotions[0]->points;
                                    $frequency = $vendorPromotion->vendor_promotions[0]->frequency;
                                }
                            ?>
                            <td>
                                <?= $this->Form->checkbox("published",['data-vendor-promotion-id'=> (isset($vendorPromotion->vendor_promotions[0]->id))?$vendorPromotion->vendor_promotions[0]->id:'','data-vendor-id'=> $loggedInUser['vendor_id'],'data-promotion-id'=> $vendorPromotion->id, 'data-points' => $points, 'checked'=>$checked, 'data-frequency'=>$frequency]) ?>
                            </td>
                            <td><?= $this->Number->format($points) ?></td>
                            <td><?= $this->Number->format($frequency) ?></td>
                            <td>
                                <?= '<a href='.$this->Url->build(['controller'=>'Promotions','action' => 'view', $vendorPromotion->id]).' class="btn btn-xs btn-success">' ?>
                                <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?php 
                                if(isset($vendorPromotion->vendor_promotions) && (!empty($vendorPromotion->vendor_promotions))){   
                                ?>
                                <span class = "action">
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorPromotion->vendor_promotions[0]->id]).' class="btn btn-xs btn-warning edit">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                </span>
                                <?php }else{ 
                                ?>
                                <span class = "action">
                                <?= $this->Html->link('<i class="fa fa-pencil fa-fw"></i>', ['action' => 'edit', $vendorPromotion->id],['escape' => false, 'class' => 'btn btn-xs btn-warning edit', 'style'=>'display:none; ']) ?>
                                </span>
                                <?php    } ?>
                                <?php 
                                if($loggedInUser['role']['name'] == 'admin' || $vendorPromotion->vendor_id == $loggedInUser['vendor_id']){

                                    if(isset($vendorPromotion->vendor_promotions) && (!empty($vendorPromotion->vendor_promotions))){

                                        $vendorPromotionId = $vendorPromotion->vendor_promotions[0]->id;
                                    }else{
                                        $vendorPromotionId = 0;
                                    }
                                ?>
                                <input type="hidden" id=<?= '"vendorPromotionId'.$vendorPromotion->id.'"' ?> value=<?= '"'.$vendorPromotionId.'"' ?>>
                                <button class="btn btn-sm btn-danger fa fa-trash-o fa-fh" onclick="checkVendorPromotion(<?= $vendorPromotion->id ?>)"></button>
                               <!--  <?= $this->Form->postLink(__(''), ['controller'=>'VendorPromotions','action' => 'delete', $vendorPromotion->vendor_promotions[0]->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $vendorPromotion->vendor_promotions[0]->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]); ?> -->
                                <?php } ?>
                            </td>
                        </tr>



                        
                        <?php } ?>
                        <?php   
                            endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th scope="col">Practice Name</th>
                            <th scope="col">Promotion Name</th>
                            <th></th>
                            <th scope="col">Points</th>
                            <th scope="col">Frequency</th>
                            
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
                {extend: 'excel', title: 'Vendor Promotions'},
                {extend: 'pdf', title: 'Vendor Promotions'},

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

            fnDrawCallback: bindVendorPromotionsCheckbox,

        });

    });
</script>

<script type="text/javascript">

    function checkVendorPromotion(promotionId){

        var vendorPromotionId = $('#vendorPromotionId'+promotionId).val();
        if(typeof vendorPromotionId == "undefined" || !parseInt(vendorPromotionId) || vendorPromotionId==""){
            swal('Warning!', "Promotion should be published before they can be deleted", "warning");
        }else{
            swal({
              title: "Are you sure?",
              text: "You want to delete vendor promotion "+vendorPromotionId,
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
            },
            function(){
            window.location = host+'VendorPromotions/delete/'+vendorPromotionId;
            });
        }
        
    }
    
</script>