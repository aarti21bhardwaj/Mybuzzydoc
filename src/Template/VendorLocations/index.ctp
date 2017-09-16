<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Locations</h5>
                        <div class="text-right list-inline">
                            <div>
                                <?php if(!$adminCheck):?>
                                    <?= $this->Form->button(__('Set Social Media Points'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => "You can set a different point value for each review site patients participate in.",'data-target' => '#setPointsModal', 'data-toggle' => 'modal', 'class' => ['btn', 'btn-md', 'btn-success']]) ?>
                                <?php endif;?>
                                <?=$this->Html->link('Add New Practice Location', ['controller' => 'VendorLocations', 'action' => 'add'],['id'=> 'newVendor','class' => ['btn', 'btn-success']])?>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                         <div class="alert alert-success"> 
                            <strong> Add each of your practice locations and the review site links you would like to use.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <th>Practice</th>
                            <th>Address</th>
                             <div class="tooltip-demo">
                                    <th class= "text-center" scope="col" data-toggle="tooltip" data-placement="top" title="The default location is your primary practice location." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Default</th>
                                </div>
                            <th><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($vendorLocations as $key => $vendorLocation): ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($vendorLocation->vendor['org_name']) ?></td>
                                <td><?= h($vendorLocation['address']) ?></td>
                                <td><?= h(($vendorLocation->is_default)?'Yes':'No') ?></td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $vendorLocation->id]).' class="btn btn-xs btn-success" id="vw'.$vendorLocation->id.'">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorLocation->id]).' class="btn btn-xs btn-warning id="edt'.$vendorLocation->id.'">' ?>
                                <i class="fa fa-pencil fa-fw" ></i>
                            </a>
                            <?php 
                                if($loggedInUser['role']['name'] != 'staff_manager'){
                                ?>
                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorLocation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vendorLocation->id),'id'=>"del".$vendorLocation->id, 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>              
                                </td>
                                <?php } ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                             <th>No.</th>
                            <th>Practice</th>
                            <th>Address</th>
                            <th>Default></th>
                    </tr>
                    </tfoot>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
            </div>

<!--Modal Window For Set Points -->
<div id="setPointsModal" class="modal inmodal fade" aria-hidden="true" role="dialog" tabindex="-1" style="display: none;" ng-app="reviewsetting" ng-controller="SettingsController as Settings">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Set Points</h4>
            </div>
            <div id= "modal-body"  class="modal-body">
                <div class="ibox-content">
                    <p>
                        <?= __('*These Points will be given to the patient based on the tasks completed while completing the Review Process for a Clinic') ?>
                    </p>
                    <?= $this->Form->create(null,['data-toggle'=>'validator','class' => 'form-horizontal', 'name' => 'rvwSet']) ?>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('review_points', __('Reviews'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-7"> 
                           <?= $this->Form->input('review_points', ['name'=> 'rvwPts','id' => 'review-points' ,'label' => false, 'type' => 'number','min'=> 0, 'required' => true, 'class' => ['form-control'], 'ng-model' => 'points.review_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?> 
                       </div>
                        <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.review_points"><strong>= {{(points.review_points/dollarValue | currency : '$' : 2)}}</strong></span>
                        </div>
                   </div>

                   <?= $this->Inspinia->horizontalRule(); ?>
                   <div class="form-group">
                        <?= $this->Form->label('rating_points', __('Ratings'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-7">
                           <?= $this->Form->input('rating_points', ['id' => 'rating-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0, 'required' => true, 'ng-model' => 'points.rating_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                       </div>

                       <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.rating_points"><strong>= {{(points.rating_points/dollarValue | currency : '$' : 2)}}</strong></span>
                        </div>
               </div>
               <?= $this->Inspinia->horizontalRule(); ?>
               <div class="form-group">
                    <?= $this->Form->label('fb_points', __('Facebook'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                       <?= $this->Form->input('fb_points', ['id' => 'fb-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0,'required' => true, 'ng-model' => 'points.fb_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                   </div>
                   <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.fb_points"><strong>= {{(points.fb_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>  
                <div class="form-group">
                    <?= $this->Form->label('gplus_points', __('Google Plus'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                       <?= $this->Form->input('gplus_points', ['id' => 'gplus-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0,'required' => true, 'ng-model' => 'points.gplus_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                    </div>
                    <div class="col-sm-3" >
                                <span class="input-group-addon" ng-if="points.gplus_points"><strong>= {{(points.gplus_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>  
                <div class="form-group">
                    <?= $this->Form->label('yelp_points', __('Yelp'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                    <?= $this->Form->input('yelp_points', ['id' => 'yelp-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0,'required' => true, 'ng-model' => 'points.yelp_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                    <?= $this->Form->hidden('vendor_id' , ['id' => 'vendor-id', 'ng-value' => $vendorId, 'ng-model' => 'points.vendor_id']) ?>
                    </div>
                    <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.yelp_points"><strong>= {{(points.yelp_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>  
                <div class="form-group">
                    <?= $this->Form->label('ratemd_points', __('RateMD'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                    <?= $this->Form->input('ratemd_points', ['id' => 'ratemd-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0,'required' => true, 'ng-model' => 'points.ratemd_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                    </div>
                    <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.ratemd_points"><strong>= {{(points.ratemd_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?> 
                <div class="form-group">
                    <?= $this->Form->label('yahoo_points', __('Yahoo'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                    <?= $this->Form->input('yahoo_points', ['id' => 'yahoo-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'min'=> 0,'required' => true, 'ng-model' => 'points.yahoo_points', 'onkeypress'=>'disAllowDotInIntegerInput(event);', 'onpaste'=>'return false;']); ?>
                    </div>
                    <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.yahoo_points"><strong>= {{(points.yahoo_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>  
                <div class="form-group">
                    <?= $this->Form->label('healthgrades_points', __('Healthgrades'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-7">
                    <?= $this->Form->input('healthgrades_points', ['id' => 'healthgrades-points' ,'label' => false, 'type' => 'number', 'class' => ['form-control'],'required' => true,'min'=> 0, 'ng-model' => 'points.healthgrades_points']); ?>
                    </div>
                    <div class="col-sm-3" >
                            <span class="input-group-addon" ng-if="points.healthgrades_points"><strong>= {{(points.healthgrades_points/dollarValue | currency : '$' : 2)}}</strong></span>
                    </div>
                    <span class = "col-lg-offset-2 col-sm-6"><p ><h4>{{message}}</h4></p></span>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?> 
                    <?= $this->Form->end() ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= $this->Form->button(__('Close'), ['id'=>'close','data-dismiss' => 'modal', 'class' => ['btn','btn-white']]) ?>
                    <?= $this->Form->button(__('Save Changes'), ['ng-disabled'=>'rvwSet.$invalid', 'id' => 'updateReviewSettings','type'=> 'button','class' => ['btn', 'btn-primary'], 'ng-click' => 'Settings.saveChanges()']) ?>
                </div>
        </div>
    </div>
</div>
<!-- End Modal-->
<script type="text/javascript">
    <?php
    $urlUpdateReviewSettings = $this->url->build(['controller' => 'Api/ReviewSettings', 'action' => 'updatePoints']);
    echo 'var urlUpdateReviewSettings = "'.$urlUpdateReviewSettings.'";';
    echo 'var vendorId = "'.$vendorId.'";';
    ?>

    $(function(){
        if(window.location.hash) {
            var hash = window.location.hash;
            $(hash).modal('toggle');
        }
    });

</script>

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
                {extend: 'excel', title: 'Practice Locations'},
                {extend: 'pdf', title: 'Practice Locations'},

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