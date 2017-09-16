<?php
use Migrations\AbstractSeed;

/**
 * SettingKeys seed.
 */
class SettingKeysSeed extends AbstractSeed
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
                            'name'=> 'Tier Program',
                            'type'=> 'boolean',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Tier Perks',
                            'type'=> 'boolean',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Manual Points Award Limit',
                            'type'=> 'boolean',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Credit Type',
                            'type'=> 'credit_type',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Instant Credit Award Limit',
                            'type'=> 'integer',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Maximum Points Award Limit',
                            'type'=> 'integer',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name'=> 'Live Mode',
                            'type'=> 'boolean',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name' => 'Milestone',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name' => 'Award Points For Perfect Survey',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name'=> 'Manual Points',
                            'type'=> 'boolean',
                            'created'=> date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')
                        ],
                        [
                            'name' => 'Custom Emails',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Gift Coupons',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Referrals',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Documents',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Products And Services',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Admin Products',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Patient Self Sign Up',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Florist One',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Referral Tier Program',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Instant Gift Coupons',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Cards',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Training Videos',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Chat',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Instant Redeem',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Express Gifts',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ],
                        [
                            'name' => 'Assessment Surveys',
                            'type' => 'boolean',
                            'created' => date('Y-m-d H:i:s'),
                            'modified'=> date('Y-m-d H:i:s')  
                        ]

        ];

        $table = $this->table('setting_keys');
        $table->insert($data)->save();
    }
}