<?php
use Migrations\AbstractMigration;

class RemoveEndTimeFromGiftCouponAwards extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('gift_coupon_awards');
        $table->removeColumn('end_time');
        $table->update();
    }
}
