<?php
use Migrations\AbstractSeed;

/**
 * Referral Template seed.
 */
class VendorReferralSettingsSeed extends AbstractSeed
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
                      [
                      'id'       => 1,
                      'vendor_id' => 1,
                      'referral_level_name' => 'level 1',
                      'referrer_award_points' => 1,
                      'referree_award_points' => 2,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 2,
                      'vendor_id' => 2,
                      'referral_level_name' => 'level 2',
                      'referrer_award_points' => 3,
                      'referree_award_points' => 0,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 3,
                      'vendor_id' => 3,
                      'referral_level_name' => 'level 3',
                      'referrer_award_points' => 5,
                      'referree_award_points' => 5,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 4,
                      'vendor_id' => 4,
                      'referral_level_name' => 'level 2',
                      'referrer_award_points' => 1,
                      'referree_award_points' => 2,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 5,
                      'vendor_id' => 4,
                      'referral_level_name' => 'level 1',
                      'referrer_award_points' => 1,
                      'referree_award_points' => 2,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 6,
                      'vendor_id' => 3,
                      'referral_level_name' => 'level 1',
                      'referrer_award_points' => 6,
                      'referree_award_points' => 3,
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      
              
                ];

        $table = $this->table('vendor_referral_settings');
        $table->insert($data)->save();
    }
}