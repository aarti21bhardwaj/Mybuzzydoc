<?php
use Migrations\AbstractSeed;

/**
 * TemplateGiftCoupons seed.
 */
class TemplateGiftCouponsSeed extends AbstractSeed
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
                    ['template_id' => '13','gift_coupon_id' => '1'],
                    ['template_id' => '13','gift_coupon_id' => '2'],
                    ['template_id' => '13','gift_coupon_id' => '3'],
                    ['template_id' => '13','gift_coupon_id' => '4'],
                    ['template_id' => '14','gift_coupon_id' => '1'],
                    ['template_id' => '14','gift_coupon_id' => '2'],
                    ['template_id' => '14','gift_coupon_id' => '3'],
                    ['template_id' => '14','gift_coupon_id' => '4'],
                    ['template_id' => '15','gift_coupon_id' => '1'],
                    ['template_id' => '15','gift_coupon_id' => '2'],
                    ['template_id' => '15','gift_coupon_id' => '3'],
                    ['template_id' => '15','gift_coupon_id' => '4'],
                    ['template_id' => '16','gift_coupon_id' => '1'],
                    ['template_id' => '16','gift_coupon_id' => '2'],
                    ['template_id' => '16','gift_coupon_id' => '3'],
                    ['template_id' => '16','gift_coupon_id' => '4'],
                    ['template_id' => '2','gift_coupon_id' => '17'],
                    ['template_id' => '3','gift_coupon_id' => '17'],
                    ['template_id' => '4','gift_coupon_id' => '17'],
                    ['template_id' => '5','gift_coupon_id' => '17'],
                    ['template_id' => '6','gift_coupon_id' => '17'],
                    ['template_id' => '23','gift_coupon_id' => '1'],
                    ['template_id' => '23','gift_coupon_id' => '2'],
                    ['template_id' => '23','gift_coupon_id' => '3'],
                    ['template_id' => '23','gift_coupon_id' => '4'],
                    ['template_id' => '24','gift_coupon_id' => '1'],
                    ['template_id' => '24','gift_coupon_id' => '2'],
                    ['template_id' => '24','gift_coupon_id' => '3'],
                    ['template_id' => '24','gift_coupon_id' => '4'],
                    ['template_id' => '25','gift_coupon_id' => '1'],
                    ['template_id' => '25','gift_coupon_id' => '2'],
                    ['template_id' => '25','gift_coupon_id' => '3'],
                    ['template_id' => '25','gift_coupon_id' => '4'],
                    ['template_id' => '26','gift_coupon_id' => '1'],
                    ['template_id' => '26','gift_coupon_id' => '2'],
                    ['template_id' => '26','gift_coupon_id' => '3'],
                    ['template_id' => '26','gift_coupon_id' => '4']
        ];

        $table = $this->table('template_gift_coupons');
        $table->insert($data)->save();
    }
}
