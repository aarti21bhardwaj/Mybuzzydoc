<?php
use Migrations\AbstractMigration;

class RemoveColumnMilestoneAndGiftCouponFromTemplates extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */

     public function up()
    {
        $table = $this->table('templates');
        $table->removeColumn('milestone')
              ->removeColumn('gift_coupon')
              ->update();
    }
}
