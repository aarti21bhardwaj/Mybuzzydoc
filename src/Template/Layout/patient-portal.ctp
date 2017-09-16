<!DOCTYPE html>
<html ng-app="inspinia">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>
    <!-- Page title set in pageTitle directive -->
    <title page-title></title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Font awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Main Inspinia CSS files -->
    <link href="css/animate.css" rel="stylesheet">
    <link id="loadBefore" href="css/style.css" rel="stylesheet">
    <link id="loadBefore" href="css/custom-style.css" rel="stylesheet">
    <link id="loadBefore" href="css/plugins/angular-notify/angular-notify.min.css" rel="stylesheet">
    <link id="loadBefore" href="node_modules/sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link id="loadBefore" href="node_modules/angular-loading-bar/build/loading-bar.min.css" rel="stylesheet">
    <!-- Inspenia Switchery for toggle buttons -->
    <?= $this->Html->css(['plugins/switchery/switchery'])?>
<style>
    #loading-bar-container {
    background: rgba(0, 0, 0, 0.5) none no-repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 99999;
    }
    #loading-bar-container .sk-spinner-wave.sk-spinner {
    font-size: 10px;
    height: 30px;
    margin-left: 50%;
    margin-top: 25%;
    text-align: center;
    width: 50px;
}
</style>

</head>

<!-- ControllerAs syntax -->
<!-- Main controller with serveral data used in Inspinia theme on diferent view -->
<body ng-controller="MainCtrl as main" class="{{$state.current.data.specialClass}}" landing-scrollspy id="page-top">

<!-- Main view  -->
<div ui-view></div>
<input type="hidden" id="r-v" value="<?= $phId ?>"/>
<input type="hidden" id="b-v" value="<?= $vendorId ?>"/>
<input type="hidden" id="v-m" value="<?= $mode ?>"/>
<div id="loading-bar" style="display: none;"><div id="loading-bar-container"><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div><div class="sk-rect2"></div><div class="sk-rect3"></div><div class="sk-rect4"></div><div class="sk-rect5"></div></div></div></div>

 
<!-- jQuery and Bootstrap -->
<?= $this->Html->script('/patient-portal/js/jquery/jquery-2.1.1.min.js') ?>
<script src='//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8'></script>
<?= $this->Html->script('/patient-portal/js/plugins/jquery-ui/jquery-ui.js') ?>
<?= $this->Html->script('/patient-portal/js/bootstrap/bootstrap.min.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/metisMenu/jquery.metisMenu.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/pace/pace.min.js') ?>
<?= $this->Html->script('/patient-portal/js/inspinia.js') ?>
<?= $this->Html->script('/patient-portal/js/angular/angular.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/angular-cookies/angular-cookies.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/ngstorage/ngStorage.min.js') ?>
<?= $this->Html->script('/patient-portal/js/angular/angular-sanitize.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/oclazyload/dist/ocLazyLoad.min.js') ?>
<?= $this->Html->script('/patient-portal/js/angular-translate/angular-translate.min.js') ?>
<?= $this->Html->script('/patient-portal/js/ui-router/angular-ui-router.min.js') ?>
<?= $this->Html->script('/patient-portal/js/bootstrap/ui-bootstrap-tpls-1.1.2.min.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/angular-idle/angular-idle.js') ?>
<?= $this->Html->script('/patient-portal/js/plugins/angular-notify/angular-notify.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/angular-sweetalert/SweetAlert.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/sweetalert/lib/sweet-alert.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/angular-timeago/dist/angular-timeago.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/angular-loading-bar/build/loading-bar.min.js') ?>
<?= $this->Html->script('/patient-portal/node_modules/angularjs-social-login/angularjs-social-login.js') ?>
<?= $this->Html->script('/patient-portal/js/app.js') ?>
<?= $this->Html->script('/patient-portal/js/config.js') ?>
<?= $this->Html->script('/patient-portal/js/translations.js') ?>
<?= $this->Html->script('/patient-portal/js/directives.js') ?>
<?= $this->Html->script('/patient-portal/js/controllers.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/UserController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/DashboardController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/ProfileController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/PasswordController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/RewardController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/ActivitiesController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/RedemptionController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/ReferralController.js') ?>
<?= $this->Html->script('/patient-portal/js/controller/EventController.js') ?>
<?= $this->Html->script('/patient-portal/js/factory/usersFactory.js') ?>
<?= $this->Html->script('/patient-portal/js/factory/vendorsFactory.js') ?>
<?= $this->Html->script('/patient-portal/js/factory/facebookFactory.js') ?>
<?= $this->Html->script('/patient-portal/js/angular-base64.min.js') ?>
<!-- Inspenia Switchery for toggle buttons -->
<?= $this->Html->script(['/patient-portal/js/plugins/switchery/switchery','/patient-portal/js/plugins/switchery/ng-switchery'])?>

</body>
</html>
