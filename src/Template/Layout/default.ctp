<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'BuzzyDoc | Application';
?>
<!DOCTYPE html>
<html>
<head>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?php echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?php //$this->Html->css('font-awesome.min.css') ?>

    <?= $this->Html->css('plugins/toastr/toastr.min.css') ?>
    <?= $this->Html->css('animate.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css(['plugins/iCheck/custom', 'plugins/steps/jquery.steps']) ?>
    <!-- Inspenia Switchery for toggle buttons -->
    <?= $this->Html->css(['plugins/switchery/switchery'])?>

    <?= $this->Html->css('super-admin.css') ?>
    <?= $this->Html->css('plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') ?>
    <?= $this->Html->css(["plugins/dataTables/datatables.min"]) ?>

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Bootstrap Tour -->
    <?= $this->Html->css(["plugins/bootstrapTour/bootstrap-tour.min"]) ?>
    <?= $this->Html->css("plugins/angular-flip-clock/angular-flip-clock") ?>

    <!-- Gritter -->
    <?= $this->Html->script('jquery-2.1.1') ?>

    <?= $this->Html->css('plugins/sweetalert/sweetalert') ?>
    <?= $this->Html->script('plugins/sweetalert/sweetalert.min') ?>
    <?= $this->Html->css('plugins/c3/c3.min.css') ?>

    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-route.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-cookies.js"></script>
    <!--User Voice-->
<!--     <script type="application/javascript" async="" defer="" src="https://by2.uservoice.com/t2/208620/web/track.js?_=1484140539961&amp;s=0&amp;c=__uvSessionData0&amp;d=eyJ1Ijp7Im8iOi0zMzB9LCJlIjp7InUiOiJodHRwOi8vc3RhZmYubGFtcGFyc2tpLmNvbS9QYXRpZW50TWFuYWdlbWVudCIsInIiOiJodHRwOi8vc3RhZmYubGFtcGFyc2tpLmNvbS9DbGllbnRNYW5hZ2VtZW50In19"></script>
-->


<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
</head>

<body <?= $sideNavData['role_name']!="admin" ? 'class="mini-navbar"' : ''?> >
    <div id="wrapper">

        <?php if($sideNavData['role_name']=="admin") {
                    echo  $this->element('Navigation/sidenav', array('sideNavData' => $sideNavData));
                }else if($sideNavData['role_name']=="staff_admin"){
                    echo  $this->element('Navigation/staffadmin-sidenav', array('sideNavData' => $sideNavData));
                }else{
                    echo  $this->element('Navigation/staffmanager-sidenav', array('sideNavData' => $sideNavData));
                }
        ?>

        <?= $this->fetch('nav') ?>
        <div id="page-wrapper" class="gray-bg">

            <?=  $this->Form->hidden('baseUrl',['id'=>'baseUrl','value'=>$this->Url->build('/', true)]); ?>
            <div class="row border-bottom">
                <?= $this->element('Navigation/topnav',array('sideNavData' => $sideNavData)); ?>
            </div>
            <?= $this->element('titleband')?>
            <div class="wrapper wrapper-content animated fadeIn" id="pageWrapper">
             <?= $this->Flash->render('auth', ['element' => 'Flash/error']) ?>
             <?= $this->Flash->render() ?>

             <?= $this->fetch('content') ?>

         </div>
         <?= $this->element('footer'); ?>
     </div>

 </div>

 <!-- Scripts -->
 <!-- Metis Menu-->

 <?= $this->IdleTimer->idleTime($idleTime) ?>
 <?= $this->Html->script('bootstrap.min') ?>
 <?= $this->Html->script('super_admin') ?>
 <?= $this->Html->script('jquery.cookie') ?>
 <?= $this->Html->script('review_settings') ?>
 <?= $this->Html->script(['/js/plugins/metisMenu/jquery.metisMenu', '/js/plugins/pace/pace.min.js', '/js/plugins/slimscroll/jquery.slimscroll.min', '/js/plugins/toastr/toastr.min', '/js/plugins/validator/validator.js', 'plugins/staps/jquery.steps.min']) ?>

 <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/css/swiper.css"> -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/css/swiper.min.css">
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.js"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.min.js"></script>
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.jquery.js"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.jquery.min.js"></script>

 <!-- Jquery UI Script-->
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

 <!-- JS cookie -->
 <?= $this->Html->script('plugins/jsCookie/js-cookie') ?>

 <!--Dashboard app scripts -->
 <?= $this->Html->script("plugins/dataTables/angular-datatables/jquery.dataTables.min") ?>
 <?= $this->Html->script(['/dashboardApp/js/app', '/dashboardApp/js/directive', '/dashboardApp/js/interceptor','plugins/dataTables/datatables.min']) ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.directive") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.instances") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.util") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.renderer") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.factory") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.options") ?>
 <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables") ?>
 <?= $this->Html->script("plugins/angular-flip-clock/angular-flip-clock.full") ?>
 <!--Dashboard app contollers -->
 <?= $this->Html->script(['/dashboardApp/js/controller/menu', '/dashboardApp/js/controller/patient', '/dashboardApp/js/controller/profile', '/dashboardApp/js/controller/redemption', '/dashboardApp/js/controller/review', '/dashboardApp/js/controller/search', '/dashboardApp/js/controller/survey', '/dashboardApp/js/controller/tier', '/dashboardApp/js/controller/patient_history', '/dashboardApp/js/controller/manual', '/dashboardApp/js/controller/edit_profile', '/dashboardApp/js/controller/referral', '/dashboardApp/js/controller/old_buzzy_history', '/dashboardApp/js/controller/send_flower', '/dashboardApp/js/controller/patient_address', '/dashboardApp/js/controller/assessment_survey']) ?>

 <!-- Dashboard app factories-->
 <?= $this->Html->script(['/dashboardApp/js/factory/patient', '/dashboardApp/js/factory/reward_products', '/dashboardApp/js/factory/search_results', '/dashboardApp/js/factory/vendor', '/dashboardApp/js/factory/plan', '/dashboardApp/js/factory/gift_coupons','/dashboardApp/js/factory/settings', '/dashboardApp/js/factory/isAuthorized','/dashboardApp/js/factory/tour']) ?>


 <!-- Bootstrap Tour -->
 <?= $this->Html->script(["plugins/bootstrapTour/bootstrap-tour.min"]) ?>

 <!-- Inspenia Switchery for toggle buttons -->
 <?= $this->Html->script(['plugins/switchery/switchery','plugins/switchery/ngSwitchery'])?>
 <!--Idle Timer Plugin-->
 <?= $this->Html->script(['plugins/idle-timer/idle-timer.min']) ?>
 <?= $this->fetch('scriptBottom'); ?>
 <?= $this->fetch('idleTimeHelperScript'); ?>

 <?= $this->Html->script('inspinia') ?>
 <script>
   $(function () {
    $('#side-menu').metisMenu();
});
</script>
<?= $this->Html->script('plugins/c3/c3.min.js') ?>
<?= $this->Html->script('plugins/d3/d3.min.js') ?>
<?php

if(isset($vendorSettings)){
    $setting = [];
    foreach ($vendorSettings as $key => $value) {
      if($value['setting_key']['type'] == 'boolean'){
        $setting[$value['setting_key']['name']] = $value['value'];
    }
}

if(isset($setting['Chat']) && $setting['Chat']){
    ?>

    <script type='text/javascript'>
        setTimeout(function(){ var fc_CSS=document.createElement('link');fc_CSS.setAttribute('rel','stylesheet');var fc_isSecured = (window.location && window.location.protocol == 'https:');var fc_lang = document.getElementsByTagName('html')[0].getAttribute('lang'); var fc_rtlLanguages = ['ar','he']; var fc_rtlSuffix = (fc_rtlLanguages.indexOf(fc_lang) >= 0) ? '-rtl' : '';fc_CSS.setAttribute('type','text/css');fc_CSS.setAttribute('href',((fc_isSecured)? 'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets1.chat.freshdesk.com')+'/css/visitor'+fc_rtlSuffix+'.css');document.getElementsByTagName('head')[0].appendChild(fc_CSS);var fc_JS=document.createElement('script'); fc_JS.type='text/javascript'; fc_JS.defer=true;fc_JS.src=((fc_isSecured)?'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets.chat.freshdesk.com')+'/js/visitor.js';(document.body?document.body:document.getElementsByTagName('head')[0]).appendChild(fc_JS);window.livechat_setting= 'eyJ3aWRnZXRfc2l0ZV91cmwiOiJoZWxwLmJ1enp5ZG9jLmNvbSIsInByb2R1Y3RfaWQiOjI1MDAwMDAwMjQ2LCJuYW1lIjoiQnV6enlEb2MgQ2xpZW50IFBvcnRhbCIsIndpZGdldF9leHRlcm5hbF9pZCI6MjUwMDAwMDAyNDYsIndpZGdldF9pZCI6ImIxMGViYzM4LWYyYmQtNGY1Zi04YjUwLTcxMDQyOWE0YTc5MSIsInNob3dfb25fcG9ydGFsIjpmYWxzZSwicG9ydGFsX2xvZ2luX3JlcXVpcmVkIjpmYWxzZSwibGFuZ3VhZ2UiOiJlbiIsInRpbWV6b25lIjoiRWFzdGVybiBUaW1lIChVUyAmIENhbmFkYSkiLCJpZCI6MjUwMDAwMDg4NDAsIm1haW5fd2lkZ2V0IjowLCJmY19pZCI6IjQ2ZjY0MjY4ZDRkYzA5MDZmY2M1YjAxZTFkZmY5NTA3Iiwic2hvdyI6MSwicmVxdWlyZWQiOjIsImhlbHBkZXNrbmFtZSI6IkludGVncmF0ZUlkZWFzIiwibmFtZV9sYWJlbCI6Ik5hbWUiLCJtZXNzYWdlX2xhYmVsIjoiTWVzc2FnZSIsInBob25lX2xhYmVsIjoiUGhvbmUiLCJ0ZXh0ZmllbGRfbGFiZWwiOiJUZXh0ZmllbGQiLCJkcm9wZG93bl9sYWJlbCI6IkRyb3Bkb3duIiwid2VidXJsIjoiaW50ZWdyYXRlaWRlYXMuZnJlc2hkZXNrLmNvbSIsIm5vZGV1cmwiOiJjaGF0LmZyZXNoZGVzay5jb20iLCJkZWJ1ZyI6MSwibWUiOiJNZSIsImV4cGlyeSI6MCwiZW52aXJvbm1lbnQiOiJwcm9kdWN0aW9uIiwiZW5kX2NoYXRfdGhhbmtfbXNnIjoiVGhhbmsgeW91ISEhIiwiZW5kX2NoYXRfZW5kX3RpdGxlIjoiRW5kIiwiZW5kX2NoYXRfY2FuY2VsX3RpdGxlIjoiQ2FuY2VsIiwic2l0ZV9pZCI6IjQ2ZjY0MjY4ZDRkYzA5MDZmY2M1YjAxZTFkZmY5NTA3IiwiYWN0aXZlIjoxLCJyb3V0aW5nIjpudWxsLCJwcmVjaGF0X2Zvcm0iOjEsImJ1c2luZXNzX2NhbGVuZGFyIjpudWxsLCJwcm9hY3RpdmVfY2hhdCI6MCwicHJvYWN0aXZlX3RpbWUiOm51bGwsInNpdGVfdXJsIjoiaGVscC5idXp6eWRvYy5jb20iLCJleHRlcm5hbF9pZCI6MjUwMDAwMDAyNDYsImRlbGV0ZWQiOjAsIm1vYmlsZSI6MSwiYWNjb3VudF9pZCI6bnVsbCwiY3JlYXRlZF9hdCI6IjIwMTctMDMtMDlUMDY6MjE6MTguMDAwWiIsInVwZGF0ZWRfYXQiOiIyMDE3LTAzLTA5VDA2OjIxOjE4LjAwMFoiLCJjYkRlZmF1bHRNZXNzYWdlcyI6eyJjb2Jyb3dzaW5nX3N0YXJ0X21zZyI6IllvdXIgc2NyZWVuc2hhcmUgc2Vzc2lvbiBoYXMgc3RhcnRlZCIsImNvYnJvd3Npbmdfc3RvcF9tc2ciOiJZb3VyIHNjcmVlbnNoYXJpbmcgc2Vzc2lvbiBoYXMgZW5kZWQiLCJjb2Jyb3dzaW5nX2RlbnlfbXNnIjoiWW91ciByZXF1ZXN0IHdhcyBkZWNsaW5lZCIsImNvYnJvd3NpbmdfYWdlbnRfYnVzeSI6IkFnZW50IGlzIGluIHNjcmVlbiBzaGFyZSBzZXNzaW9uIHdpdGggY3VzdG9tZXIiLCJjb2Jyb3dzaW5nX3ZpZXdpbmdfc2NyZWVuIjoiWW91IGFyZSB2aWV3aW5nIHRoZSB2aXNpdG9y4oCZcyBzY3JlZW4iLCJjb2Jyb3dzaW5nX2NvbnRyb2xsaW5nX3NjcmVlbiI6IllvdSBoYXZlIGFjY2VzcyB0byB2aXNpdG9y4oCZcyBzY3JlZW4uIiwiY29icm93c2luZ19yZXF1ZXN0X2NvbnRyb2wiOiJSZXF1ZXN0IHZpc2l0b3IgZm9yIHNjcmVlbiBhY2Nlc3MgIiwiY29icm93c2luZ19naXZlX3Zpc2l0b3JfY29udHJvbCI6IkdpdmUgYWNjZXNzIGJhY2sgdG8gdmlzaXRvciAiLCJjb2Jyb3dzaW5nX3N0b3BfcmVxdWVzdCI6IkVuZCB5b3VyIHNjcmVlbnNoYXJpbmcgc2Vzc2lvbiAiLCJjb2Jyb3dzaW5nX3JlcXVlc3RfY29udHJvbF9yZWplY3RlZCI6IllvdXIgcmVxdWVzdCB3YXMgZGVjbGluZWQgIiwiY29icm93c2luZ19jYW5jZWxfdmlzaXRvcl9tc2ciOiJTY3JlZW5zaGFyaW5nIGlzIGN1cnJlbnRseSB1bmF2YWlsYWJsZSAiLCJjb2Jyb3dzaW5nX2FnZW50X3JlcXVlc3RfY29udHJvbCI6IkFnZW50IGlzIHJlcXVlc3RpbmcgYWNjZXNzIHRvIHlvdXIgc2NyZWVuICIsImNiX3ZpZXdpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgY2FuIHZpZXcgeW91ciBzY3JlZW4gIiwiY2JfY29udHJvbGxpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgaGFzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAiLCJjYl92aWV3X21vZGVfc3VidGV4dCI6IllvdXIgYWNjZXNzIHRvIHRoZSBzY3JlZW4gaGFzIGJlZW4gd2l0aGRyYXduICIsImNiX2dpdmVfY29udHJvbF92aSI6IkFsbG93IGFnZW50IHRvIGFjY2VzcyB5b3VyIHNjcmVlbiAiLCJjYl92aXNpdG9yX3Nlc3Npb25fcmVxdWVzdCI6IkFnZW50IHNlZWtzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAifX0=';; }, 3000);
    </script>

    <?php }
    }?>
</body>

</html>
