<?php
use Migrations\AbstractSeed;

/**
 * AuthorizeNetProfiles seed.
 */
class AuthorizeNetProfilesSeed extends AbstractSeed
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
            'user_id' =>'1',
            'profile_identifier' =>'1500632304',
            'payment_profileid' => '1500566910',
            'is_card_setup' => 1,
            'created' => date('Y-m-d H:i:s'),
            'modified'=> date('Y-m-d H:i:s')
        ],
        [
            'user_id' =>'2',
            'profile_identifier' =>'1500632363',
            'payment_profileid' => '1500561356',
            'is_card_setup' => 1,
            'created' => date('Y-m-d H:i:s'),
            'modified'=> date('Y-m-d H:i:s')
        ],
        [
            'user_id' =>'3',
            'profile_identifier' =>'1500632380',
            
        ],
        ];

        $table = $this->table('authorize_net_profiles');
        $table->insert($data)->save();
    }
}
