<?php
use Migrations\AbstractSeed;

/**
 * LegacyRewards seed.
 */
class LegacyRewardsSeed extends AbstractSeed
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
                      'name'    => 'Coins',
                      'vendor_id'=> 1,
                      'reward_category_id'=> 1,
                      'product_type_id'=> 2,
                      'points'=> 50, 
                      'amount' => 1,
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      ],
                      [
                      'name'    => 'Cards',
                      'vendor_id'=> 1,
                      'reward_category_id'=> 1,
                      'product_type_id'=> 2,
                      'points'=> 50,
                      'amount' => 1, 
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      ],
                      [
                      'name'    => 'Custom Gift Card',
                      'vendor_id'=> 1,
                      'reward_category_id'=> 1,
                      'product_type_id'=> 3,
                      'amount'=> 0, 
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      ],
                      [
                      'name'    => 'Amazon/Tango',
                      'vendor_id'=> 1,
                      'reward_category_id'=> 2,
                      'product_type_id'=> 1,
                      'amount'=> 0, 
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      ],
        ];

        $table = $this->table('legacy_rewards');
        $table->insert($data)->save();
    }
}
