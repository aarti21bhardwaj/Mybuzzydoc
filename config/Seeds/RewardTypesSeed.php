<?php
use Migrations\AbstractSeed;

/**
 * RewardTypes seed.
 */
class RewardTypesSeed extends AbstractSeed
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

            [

                'type'    => 'Points',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s'),
              
            ],
            [

                'type'    => 'GiftCoupons',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s'),
              
            ],

        ];

        $table = $this->table('reward_types');
        $table->insert($data)->save();
    }
}
