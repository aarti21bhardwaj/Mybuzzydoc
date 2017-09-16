 <?= $this->Html->script(['vendor-florist-settings', 'tours/flowerSetting-tour']) ?>
<div class="row" ng-app="vendorFloristSettings" ng-controller="VendorFloristSettingsController as Settings">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = 'addflowerSetting'><?= __('Floral Settings') ?></h5>
                 <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div>
            </div>
            <div class="ibox-content"> 
                <form class="form-horizontal" name="vendorFloristSettings" data-toggle= "validator" ng-submit = "saveSettings()">
                    <div class="form-group"><label class="col-lg-2 control-label" id = 'flower'>Flower</label>
                        <div class="col-lg-10"><button data-toggle="modal" data-target = "#flowerModal" type="button"><img ng-src="{{flowerImgSrc}}"></button><br>
                        <i class="fa fa-lg fa-info-circle"></i><strong> Browse and select arrangements.</strong>
                        </div>
                    </div>
                        <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group"><label class="col-lg-2 control-label" id='message'>Message</label>
                        <div class="col-lg-10">
                        <textarea type="text" class="form-control" required ng-model = "floristSetting.message"></textarea> 
                        </div>
                    </div>
                        <?= $this->Inspinia->horizontalRule(); ?>
                        <h3 style="margin-left: 60px;">Add Billing Address (Your Practice Address)</h3><br>
                    <div class="form-group"><label class="col-lg-2 control-label"  id='address'>Address</label>
                        <div class="col-lg-10"><input type="text" placeholder="Address" class="form-control" required ng-model = "floristSetting.address1">
                        </div>
                    </div>
                        <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group"><label class="col-lg-2 control-label">City</label>
                        <div class="col-lg-10"><input type="text" placeholder="City" class="form-control" required ng-model = "floristSetting.city">
                        </div>
                    </div>  
                        <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group"><label class="col-lg-2 control-label">State</label>
                        <div class="col-lg-10"><select class="form-control m-b" ng-model="floristSetting.state" required ng-options="state.abbreviation as state.name for state in states"><option value="">--Please Select--</option></select>
                        </div>    
                    </div>
                        <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group"><label class="col-lg-2 control-label">Country</label>
                        <div class="col-lg-10"><input type="text" readonly='true' placeholder="Country" class="form-control" ng-model = "floristSetting.country" ng-init="floristSetting.country = 'US'" value="US">
                        </div>
                    </div>
                        <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group"><label class="col-lg-2 control-label">Zipcode</label>
                        <div class="col-lg-10"><input type="number" placeholder="Zipcode" data-minlength="3" class="form-control" required ng-model = "floristSetting.zipcode" ng-pattern="numRegex" maxlength="6">
                        </div>
                    </div>    
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-2">
                            <button class="btn btn-success" type="submit" ng-disabled = "vendorFloristSettings.$invalid || !saveBtn" id='save'>Save Changes</button>
                            </div>
                        </div>
        </div>
    </div>
    <!-- Modal starts Here-->
    <div class="modal inmodal fade" id="flowerModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">Choose Flowers to Send</h4>
                    <small class="font-bold">Select a flower based on the category</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                     <form>
                        <div class="form-group">
                          <label class="col-lg-2 control-label">Choose Category</label>
                          <div class="col-lg-10">
                            <select class="form-control" ng-model = "flowers.category" required ng-options="key for (key , value) in flowers.categories">
                              <option value="">--Please Select Category--</option>
                            </select>
                          </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                          <label class="col-lg-2 control-label">Choose Sub-Category</label>
                          <div class="col-lg-10">
                            <select class="form-control" ng-model = "flowers.subCategory" required ng-options="key for (key , value) in flowers.category" ng-change = "getProducts()">
                              <option value="">--Please Select Sub-Category--</option>
                            </select>
                          </div>
                        </div>
                        <br></br>
                        </form>
                        <div class="ibox-content" ng-show="wait">
                            <div class="spiner-example">
                                <div class="sk-spinner sk-spinner-rotating-plane"></div>
                            </div>
                        </div>
                        <div class = "text-center" ng-hide="wait">
                        <div ng-if= '!productCount'>
                        <h4></h4>
                        </div>
                        <div ng-if= 'productCount == 0'>
                        <h4>No flowers found. Please try a different category</h4>
                        </div>
                        <div ng-if= 'productCount > 0'>
                        <h4>Select Flower</h4>
                        </div>
                        </div>

                          <div class="row" ng-hide="wait">
                            <div class="col-md-3" ng-repeat="media in productList">
                                <div class="ibox">
                                    <div class="ibox-content product-box" ng-click="selectProduct(media)" data-dismiss="modal">
                                        <div>
                                            <img ng-src="{{media.SMALL}}" />
                                        </div>
                                        <div class="product-desc" style="padding:0px !important">
                                            <span class="product-price">
                                                ${{media.PRICE}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                     </div>                                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Ends Here -->
</div>