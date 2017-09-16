<?php
use Migrations\AbstractSeed;

/**
 * GiftCoupons seed.
 */
class GiftCouponsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    ['points' => '1250','expiry_duration' => '180','description' => '$25 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '2500','expiry_duration' => '180','description' => '$50 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '3750','expiry_duration' => '180','description' => '$75 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '5000','expiry_duration' => '180','description' => '$100 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '1250','expiry_duration' => '180','description' => '$25 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '2500','expiry_duration' => '180','description' => '$50 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '3750','expiry_duration' => '180','description' => '$75 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '5000','expiry_duration' => '180','description' => '$100 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '1250','expiry_duration' => '180','description' => '$25 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '2500','expiry_duration' => '180','description' => '$50 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '3750','expiry_duration' => '180','description' => '$75 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '5000','expiry_duration' => '180','description' => '$100 Gift Certificate','vendor_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '1250','expiry_duration' => '180','description' => '$25 Gift Certificate','vendor_id' => '3','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '2500','expiry_duration' => '180','description' => '$50 Gift Certificate','vendor_id' => '3','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '3750','expiry_duration' => '180','description' => '$75 Gift Certificate','vendor_id' => '3','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '5000','expiry_duration' => '180','description' => '$100 Gift Certificate','vendor_id' => '3','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '250','expiry_duration' => '180','description' => '$5 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL],
                    ['points' => '500','expiry_duration' => '180','description' => '$10 Gift Certificate','vendor_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'reason' => NULL]

        ];

        $table = $this->table('gift_coupons');
        $table->insert($data)->save();
    }
}
