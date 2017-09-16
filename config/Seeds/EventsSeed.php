<?php
use Migrations\AbstractSeed;

/**
 * Events seed.
 */
class EventsSeed extends AbstractSeed
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
                'name'=> 'Welcome BuzzyDoc',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'ReviewLink',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Reset Password',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'ReferralLink',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'CreditCardChargeSuccess',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'CreditCardChargeFailure',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'TierChange',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'TierYearChange',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Welcome Patient',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Patient Reset Password',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Instant Rewards',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')

            ],
            [
                'name'=> 'Self Sign Up Notification',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')

            ],
            [
                'name'=> 'Redemption Notification',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Patient Reported Outcomes Survey',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Patient Redemption',
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ]

        ];

        $table = $this->table('events');
        $table->insert($data)->save();
    }
}
