<?php
use Migrations\AbstractMigration;

class AddGiftCouponTypeIdToGiftCoupons extends AbstractMigration
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
        $table = $this->table('gift_coupons');
        $table->addColumn('gift_coupon_type_id', 'integer', [
            'default' => 1,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
