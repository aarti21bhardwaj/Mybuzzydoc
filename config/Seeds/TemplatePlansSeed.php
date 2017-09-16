<?php
use Migrations\AbstractSeed;

/**
 * TemplatePlans seed.
 */
class TemplatePlansSeed extends AbstractSeed
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
                    ['plan_id' => '1','template_id' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '1','template_id' => '2','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '1','template_id' => '3','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '1','template_id' => '4','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '1','template_id' => '5','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '1','template_id' => '6','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '7','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '8','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '9','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '10','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '11','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '12','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '13','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '14','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '15','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '16','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '17','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '18','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '19','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '20','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '21','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '2','template_id' => '22','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '23','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '24','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['plan_id' => '3','template_id' => '26','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')]
        ];

        $table = $this->table('template_plans');
        $table->insert($data)->save();
    }
}
