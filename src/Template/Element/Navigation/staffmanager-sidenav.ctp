<?php
$this->start('nav');
$firstName = \Cake\Utility\Inflector::humanize($sideNavData['first_name']);
?>

<?php $plan = [];
foreach ($vendorPlanFeatures as $key => $value) {
  $plan[$value['feature']['name']] = true;
} ?>

<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element">
          <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
            <span class="clear">
              <span class="block m-t-xs">
                <strong class="font-bold">
                  <h2><?= $firstName ?></h2>
                </strong>
              </span>
              <span class="text-muted text-xs block">
                <?= $sideNavData['role_label']?>
                <b class="caret"></b>
              </span>
            </span>
          </a>
          <ul class="dropdown-menu animated fadeInRight m-t-xs">
            <li><?= $this->Html->link(__('Profile'), ['controller' => 'Users', 'action' => 'edit', $sideNavData['id']]) ?></li>
            <li class="divider"></li>
            <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
          </ul>
        </div>
        <div class="logo-element">
          <?= $this->Html->image('icon-reverse-low-rez.png', ['style' => 'width:30px; height:30px;', 'alt'=>'image'])?>
        </div>
      </li>
      <li>
        <a onclick="letsRedirect('/users/dashboard')" href="<?php echo $this->Url->build(["controller" => "users","action" => "dashboard"]);?>#/"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
      </li>
     
   
    </div>
  </nav>

  <?php
  $this->end();

  $this->Html->scriptStart(['block' => 'scriptBottom']);
  echo "$(function () {
    $('#side-menu').metisMenu();
  });";
  $this->Html->scriptEnd();

  ?>
