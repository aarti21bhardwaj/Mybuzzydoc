<?php
use Migrations\AbstractMigration;

class ChangeModifiedInVendorPlans extends AbstractMigration
{
   
   public function up()
    {
        $vendorPromotions = $this->table('vendor_plans');
        $vendorPromotions->changeColumn('modified', 'datetime',array('null' => false))
                         ->changeColumn('end_time', 'datetime',array('null' => true))
                         ->save();
    }
    public function down(){
        $vendorPromotions = $this->table('vendor_plans');
        $vendorPromotions->changeColumn('modified', 'datetime',array('null' => true))
                         ->changeColumn('end_time', 'datetime',array('null' => false))
                         ->save(); 
    }
}
