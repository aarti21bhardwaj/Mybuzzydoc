<?php
use Migrations\AbstractSeed;

/**
 * ProductType seed.
 */
class VendorsSeed extends AbstractSeed
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
                      'org_name' => 'admin',
                      'reward_url' => NULL,
                      'threshold_value' => 1,
                      'is_legacy' => 1,
                      'people_hub_identifier' => 1,
                      'status'   => 1,
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      'min_deposit' => 1,
                      ],
                      [
                      'id'       => 2,
                      'org_name' => 'Charizad',
                      'reward_url' => NULL,
                      'threshold_value' => 1500,
                      'is_legacy' => 1,
                      'people_hub_identifier' => 2,
                      'status'   => 1,
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      'min_deposit' => 3000,
                      ],
                      [
                      'id'       => 3,
                      'org_name' => 'Blastoise',
                      'reward_url' => NULL,
                      'threshold_value' => 1000,
                      'is_legacy' => 1,
                      'people_hub_identifier' => 3,
                      'status'   => 1,
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      'min_deposit' => 2000,
                      ],
                      [
                      'id'       => 4,
                      'org_name' => 'Venasaur',
                      'reward_url' => NULL,
                      'threshold_value' => 2000,
                      'is_legacy' => 1,
                      'people_hub_identifier' => 4,
                      'status'   => 1,
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      'is_deleted' => NULL,
                      'min_deposit' => 4000,
                      ]
                ];

        $table = $this->table('vendors');
        $table->insert($data)->save();
    }
}
