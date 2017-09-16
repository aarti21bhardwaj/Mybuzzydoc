<?php $setting = [];
//pr($vendorSettings);die;
foreach ($vendorSettings as $key => $value) {
  if($value['setting_key']['type'] == 'boolean'){
    $setting[$value['setting_key']['name']] = $value['value'];
  }
//pr($setting); die;
}
?>

<?php $plan = [];
foreach ($vendorPlanFeatures as $key => $value) {
  $plan[$value['feature']['name']] = true;
} ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl">
                <h2 class="text-navy font-bold"><strong>Practice Settings</strong></h2>
                <h3 class="text-navy font-bold"><strong>Easily manage the rest of your practice program setting from this page.</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Practice Info</h2>
                <p class="m-xs"> Manage the displayed settings for your practice.</p><br><br><br>
                 <?=$this->Html->link('EDIT', ['controller'=>'Vendors','action' => 'edit',$topHeader['vendor_id']],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content">
                <h2 class="font-bold p-md">Locations</h2>
                <p class="m-xs"> Manage your practice locations.</p><br><br><br>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'VendorLocations','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD LOCATIONS', ['controller'=>'VendorLocations','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
    <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Staff Users</h2>
                <p class="m-xs"> Manage the staff members using your program.</p><br><br><br>
                <?=$this->Html->link('VIEW USERS', ['controller'=>'users','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD USERS', ['controller'=>'users','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
           
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Documents</h2>
                <p class="m-xs">Manage the documents/files available to your patients.</p><br><br><br>
                <?php if(isset($setting['Documents']) && $setting['Documents']) {?>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'VendorDocuments','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <?=$this->Html->link('ADD NEW', ['controller'=>'VendorDocuments','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
    <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Email Templates</h2>
                <p class="m-xs"> Manage the displayed settings for your practice.</p><br><br><br>
                <?php if(isset($plan['email']) && isset($setting['Custom Emails']) && $setting['Custom Emails']) {?>
                <?=$this->Html->link('VIEW ALL', ['controller'=>'VendorEmailSettings','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?>
                &nbsp; &nbsp;
                <!-- <?=$this->Html->link('ADD TEMPLATE', ['controller'=>'VendorEmailSettings','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']])?> -->
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
           
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Payment Management</h2>
                <p class="m-xs">Manage the payment details for your practice.</p><br><br><br>
                <?= $this->Html->link('MANAGE', ['controller'=>'authorize_net_profiles','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
    <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Sending Flowers</h2>
                <p class="m-xs"> Manage the settings for sending flowers from your practice.</p><br><br><br>
                <?php if(isset($setting['Florist One']) && $setting['Florist One']) {?>
                <?= $this->Html->link(__('EDIT'), ['controller'=>'VendorFloristSettings','action' => 'add'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                &nbsp; &nbsp;
                <?= $this->Html->link(__('APPROVE ORDERS'), ['controller'=>'VendorFloristOrders','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
           
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Rewards Cards</h2>
                <p class="m-xs">Manage the physical cards for your practice.</p><br><br><br>
                 <?php if(isset($setting['Cards']) && $setting['Cards']) {?>
               <?= $this->Html->link(__('VIEW CARDS'), ['controller'=>'VendorCards','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                &nbsp; &nbsp;
                <?= $this->Html->link(__('CARD REQUESTS'), ['controller'=>'VendorCardRequests','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                <?php } else {?>
                <?= $this->Form->label('', __('INACTIVE'), ['class' => ['badge-warning', 'btn-rounded', 'btn-w-m'], 'style' => 'padding-top:7px; padding-bottom:5px;', 'data-toggle'=> 'tooltip', 'data-placement'=> 'top', 'title'=>'Contact BuzzyDoc to access this feature.']); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
    <div class="ibox float-e-margins">
           <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Upload Patient File</h2>
                <p class="m-xs">Easily import patients into your rewards program.</p><br><br><br>
                <?= $this->Html->link('UPLOAD', ['controller'=>'Patients','action' => 'index'],['class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-xl content" >
                <h2 class="font-bold p-md">Patient Portal</h2>
                <p class="m-xs">Add the code to your practice website to open your patient portal in an iframe.</p>
                <code><p id="pp-iframe"><<span>iframe </span><span>src="<?php echo ($patientPortalUrl); ?>"</span> width="100%" height="1000px"><<span>/iframe</span>></p></code>
                <?= $this->Html->link('Visit Portal', $patientPortalUrl,["target"=>'_blank','class' => ['btn', 'btn-w-m btn-primary btn-rounded']]) ?>
                <button id="demo" class="btn btn-w-m btn-primary btn-rounded" onclick="copyToClipboard(document.getElementById('pp-iframe').innerHTML)">Copy Code</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .content{
        height:300px;
        font-size: 15px;
    }
    .divider{
    width:5px;
    height:auto;
    display:inline-block;
}
</style>
<script>
  function copyToClipboard(text) {
     var doc = new DOMParser().parseFromString(text, 'text/html');
     var text = doc.body.textContent;
    window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
  }
</script>