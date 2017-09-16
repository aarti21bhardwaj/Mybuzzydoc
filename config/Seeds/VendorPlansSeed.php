<?php
use Migrations\AbstractSeed;

/**
 * VendorPlans seed.
 */
class VendorPlansSeed extends AbstractSeed
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
                      'vendor_id'    => 1,
                      'plan_id'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ],
                    [
                      'vendor_id'    => 2,
                      'plan_id'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ],
                    [
                      'vendor_id'    => 3,
                      'plan_id'=> 2,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ],
                    [
                      'vendor_id'    => 4,
                      'plan_id'=> 2,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ]
        ];

        $table = $this->table('vendor_plans');
        $table->insert($data)->save();
    }
}
