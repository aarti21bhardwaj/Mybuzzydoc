<?php
use Migrations\AbstractSeed;

/**
 * PlanFeatures seed.
 */
class PlanFeaturesSeed extends AbstractSeed
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
                'plan_id' => 1,
                'feature_id'=> 2
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 3
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 5
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 6
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 8
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 9
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 1
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 2
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 4
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 5
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 6
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 7
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 8
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 9
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 10
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 13
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 14
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 1
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 2
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 3
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 4
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 5
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 6
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 7
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 8
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 9
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 10
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 11
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 12
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 13
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 14
            ],
            [
                'plan_id' => 1,
                'feature_id'=> 15
            ],
            [
                'plan_id' => 2,
                'feature_id'=> 15
            ],
            [
                'plan_id' => 3,
                'feature_id'=> 15
            ],
        ];

        $table = $this->table('plan_features');
        $table->insert($data)->save();
    }
}
