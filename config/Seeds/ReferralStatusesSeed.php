<?php
use Phinx\Seed\AbstractSeed;

/**
 * Roles seed.
 */
class ReferralStatusesSeed extends AbstractSeed
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
                      
                      'status' => 'Pending'
                    ],
                    [
                      'status' => 'Complete' 
                    ],
                    [
                      'status' => 'Not Ready Yet'
                    ]
        ];

        $table = $this->table('referral_statuses');
        $table->insert($data)->save();
    }
}
