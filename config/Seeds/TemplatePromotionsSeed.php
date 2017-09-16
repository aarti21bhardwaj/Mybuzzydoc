<?php
use Migrations\AbstractSeed;

/**
 * TemplatePromotions seed.
 */
class TemplatePromotionsSeed extends AbstractSeed
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
                        ['template_id' => '7','promotion_id' => '6'],
                        ['template_id' => '7','promotion_id' => '7'],
                        ['template_id' => '7','promotion_id' => '8'],
                        ['template_id' => '7','promotion_id' => '9'],
                        ['template_id' => '7','promotion_id' => '10'],
                        ['template_id' => '7','promotion_id' => '11'],
                        ['template_id' => '8','promotion_id' => '6'],
                        ['template_id' => '8','promotion_id' => '12'],
                        ['template_id' => '9','promotion_id' => '6'],
                        ['template_id' => '9','promotion_id' => '13'],
                        ['template_id' => '10','promotion_id' => '6'],
                        ['template_id' => '10','promotion_id' => '12'],
                        ['template_id' => '11','promotion_id' => '6'],
                        ['template_id' => '11','promotion_id' => '12'],
                        ['template_id' => '12','promotion_id' => '6'],
                        ['template_id' => '12','promotion_id' => '11'],
                        ['template_id' => '13','promotion_id' => '6'],
                        ['template_id' => '13','promotion_id' => '13'],
                        ['template_id' => '14','promotion_id' => '6'],
                        ['template_id' => '14','promotion_id' => '12'],
                        ['template_id' => '15','promotion_id' => '6'],
                        ['template_id' => '15','promotion_id' => '12'],
                        ['template_id' => '16','promotion_id' => '6'],
                        ['template_id' => '16','promotion_id' => '12'],
                        ['template_id' => '17','promotion_id' => '6'],
                        ['template_id' => '17','promotion_id' => '7'],
                        ['template_id' => '17','promotion_id' => '8'],
                        ['template_id' => '17','promotion_id' => '9'],
                        ['template_id' => '17','promotion_id' => '14'],
                        ['template_id' => '17','promotion_id' => '16'],
                        ['template_id' => '18','promotion_id' => '6'],
                        ['template_id' => '18','promotion_id' => '15'],
                        ['template_id' => '19','promotion_id' => '6'],
                        ['template_id' => '19','promotion_id' => '11'],
                        ['template_id' => '20','promotion_id' => '6'],
                        ['template_id' => '20','promotion_id' => '15'],
                        ['template_id' => '21','promotion_id' => '6'],
                        ['template_id' => '21','promotion_id' => '15'],
                        ['template_id' => '22','promotion_id' => '6'],
                        ['template_id' => '22','promotion_id' => '14'],
                        ['template_id' => '24','promotion_id' => '6'],
                        ['template_id' => '24','promotion_id' => '15'],
                        ['template_id' => '23','promotion_id' => '6'],
                        ['template_id' => '23','promotion_id' => '11'],
                        ['template_id' => '25','promotion_id' => '6'],
                        ['template_id' => '25','promotion_id' => '15'],
                        ['template_id' => '26','promotion_id' => '6'],
                        ['template_id' => '26','promotion_id' => '15']
                ];

        $table = $this->table('template_promotions');
        $table->insert($data)->save();
    }
}