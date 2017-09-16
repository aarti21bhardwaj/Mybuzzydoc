<?php 
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
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('plugins/toastr/toastr.min.css') ?>
    <?= $this->Html->css('animate.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('super-admin.css') ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body class="gray-bg">
  <?= $this->Flash->render() ?>
  <?= $this->Flash->render('auth', ['element' => 'Flash/error']) ?>
    <?= $this->fetch('content'); ?>

    <?= $this->Html->script('jquery-2.1.1') ?>
    <?= $this->Html->script('bootstrap.min') ?>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <?= $this->Html->script(['/js/plugins/metisMenu/jquery.metisMenu', '/js/plugins/pace/pace.min.js', '/js/plugins/slimscroll/jquery.slimscroll.min', '/js/plugins/toastr/toastr.min', '/js/plugins/validator/validator.js']) ?>
    <?= $this->Html->script('super_admin') ?>
    <?= $this->Html->script(['/js/plugins/metisMenu/jquery.metisMenu', '/js/plugins/pace/pace.min.js', '/js/plugins/slimscroll/jquery.slimscroll.min', '/js/plugins/toastr/toastr.min', '/js/plugins/validator/validator.js']) ?>

    <!--Idle Timer Plugin-->
    <?= $this->Html->script(['plugins/idle-timer/idle-timer.min.js']) ?>
</body>

</html>