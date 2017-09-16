<?php
$this->start('nav');
?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                   <?= $this->Html->image('user-small.png')?>
               </span>
               <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= $sideNavData['name']?></strong>
                </span> <span class="text-muted text-xs block"><?= $sideNavData['role']?> <b class="caret"></b></span> </span> </a>
            </div>
            <div class="logo-element">
                PH
            </div>
        </li>
        <?php if($sideNavData['role']=="admin") {?>
           <li>
                <a href="<?php echo $this->Url->build(["controller" => "Admin","action" => "index"]);?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></span></a>
           </li>
           <li class="">
           <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Register new Reseller</span></span></a>
           <ul class="nav nav-second-level">
            <li><?= $this->Html->link(__('List Resellers'), ['controller'=>'Resellers','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Register New Reseller'), ['controller'=>'Resellers','action' => 'add']) ?></li>
        </ul>
    </li>
           <li class="">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Vendors</span></span></a>
            <ul class="nav nav-second-level">
                <li><?= $this->Html->link(__('View All'), ['controller'=>'Vendors','action' => 'index']) ?></li>
                <li><?= $this->Html->link(__('Register New Vendor'), ['controller'=>'Vendors','action' => 'Add']) ?></li>
            </ul>
        </li>
        <li class="">
            <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Cards</span></span></a>
            <ul class="nav nav-second-level">
                <li><?= $this->Html->link(__('List Card Series'), ['controller'=>'CardSeries','action' => 'index']) ?></li>
                <li><?= $this->Html->link(__('Add New Card Series'), ['controller'=>'CardSeries','action' => 'add']) ?></li>
            </ul>
        </li>
        <li class="">
           <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Assign Card To Vendor</span></span></a>
           <ul class="nav nav-second-level">
            <li><?= $this->Html->link(__('List Assigned Cards'), ['controller'=>'VendorCards','action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Assign Card to vendor'), ['controller'=>'VendorCards','action' => 'add']) ?></li>
        </ul>
    </li>

    
    <?php
} 
?>
</ul>

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