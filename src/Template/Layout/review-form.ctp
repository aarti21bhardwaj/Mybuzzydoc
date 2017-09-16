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
use Cake\Core\Configure;
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
    <?= $this->Html->css(['style.css', 'plugins/sweetalert/sweetalert']) ?>

    <?= $this->Html->css(['plugins/ui-swiper/angular-ui-swiper']) ?>
    <!-- Sweet alert -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Gritter -->
    <?= $this->Html->script('jquery-2.1.1') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?php if($this->fetch('title') === 'VendorReviews'): ?>
      <meta property="og:url"                content= <?= $publicUrl ?> />
      <meta property="og:type"               content="review" />
      <meta property="og:title"              content=<?= $this->fetch('title') ?> />
      <?php if(isset($vendorLocation->address)):?>
      <meta property="og:description"        content= <?= '"Rating and review of '.$vendorLocation->address.'"'?>  />
      <?php endif;?>
      <meta property="og:image"              content= "" />
    <?php endif; ?>

</head>
<body class= "pace-done mini-navbar">


    <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : <?php echo Configure::read('facebookAppId')?>,
      xfbml      : true,
      version    : 'v2.7'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
    
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-route.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-cookies.js"></script>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/jquery.dataTables.min") ?>
        <?= $this->Html->script(['plugins/dataTables/datatables.min']) ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.directive") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.instances") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.util") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.renderer") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.factory") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables.options") ?>
        <?= $this->Html->script("plugins/dataTables/angular-datatables/angular-datatables") ?>
        <?=  $this->Form->hidden('baseUrl',['id'=>'baseUrl','value'=>$this->Url->build('/', true)]); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
            <?php 
            $underscore = \Cake\Utility\Inflector::underscore($this->request->params['controller']);
            $humanize = \Cake\Utility\Inflector::humanize($underscore); ?>
            <?php if($underscore == 'vendors'){ 
                    $humanize = 'Sign Up';
                  }elseif($humanize == 'Vendor Instant Gift Coupon Settings'){ 
                    $humanize = 'Instant Rewards';
                  }elseif($humanize == 'Vendor Reviews'){ 
                    $humanize = 'Let us know how we are doing!';
                  }else if('Assessment Survey Instances'){
                    $humanize = 'Reporting Survey';
                  } ?>
              <h2><?= $humanize ?></h2>
          </div>
        </div>
            <div class="wrapper wrapper-content animated fadeIn">
                     <?= $this->Flash->render('auth', ['element' => 'Flash/error']) ?>
                     <?= $this->Flash->render() ?>
                        
                            <?= $this->fetch('content') ?>
                        
                </div>
        <?= $this->element('footer'); ?>
        </div>
    
    </div>

    <!-- Scripts -->
    <!-- Metis Menu-->
    <!-- Load Angular-->
    
    <?= $this->Html->script('bootstrap.min') ?>
    <?= $this->Html->script(['super_admin', 'plugins/sweetalert/sweetalert.min.js']) ?>
    <?= $this->Html->script(['/js/plugins/metisMenu/jquery.metisMenu', '/js/plugins/pace/pace.min.js', '/js/plugins/slimscroll/jquery.slimscroll.min', '/js/plugins/toastr/toastr.min', '/js/plugins/validator/validator.js']) ?>

   
    <!--Idle Timer Plugin-->
    <?= $this->Html->script(['plugins/idle-timer/idle-timer.min.js']) ?>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <?= $this->fetch('scriptBottom'); ?>
    <?= $this->Html->script(['plugins/ui-swiper/angular-ui-swiper']) ?>
    <?= $this->Html->script('inspinia') ?>       

       <script>
           $(function () {
            $('#side-menu').metisMenu();
          });
       </script>
</body>

</html>
