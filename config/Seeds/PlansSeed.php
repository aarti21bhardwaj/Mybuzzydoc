<?php
use Migrations\AbstractSeed;

/**
 * Plans seed.
 */
class PlansSeed extends AbstractSeed
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
                'name'=> 'Good',
            ],
            [
                'name'=> 'Better',
            ],
            [
                'name'=> 'Best',
            ]
        ];

        $table = $this->table('plans');
        $table->insert($data)->save();
    }
}
