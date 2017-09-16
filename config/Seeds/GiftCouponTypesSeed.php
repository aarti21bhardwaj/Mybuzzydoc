<?php
use Migrations\AbstractSeed;

/**
 * GiftCouponTypes seed.
 */
class GiftCouponTypesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    ['name' => 'standard'],
                    ['name' => 'instant']
                ];

        $table = $this->table('gift_coupon_types');
        $table->insert($data)->save();
    }
}
