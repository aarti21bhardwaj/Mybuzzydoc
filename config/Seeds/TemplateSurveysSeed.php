<?php
use Migrations\AbstractSeed;

/**
 * TemplateSurveys seed.
 */
class TemplateSurveysSeed extends AbstractSeed
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
                    ['template_id' => '7','survey_id' => '1'],
                    ['template_id' => '10','survey_id' => '3'],
                    ['template_id' => '11','survey_id' => '4'],
                    ['template_id' => '12','survey_id' => '5'],
                    ['template_id' => '20','survey_id' => '9'],
                    ['template_id' => '21','survey_id' => '10'],
                    ['template_id' => '8','survey_id' => '13'],
                    ['template_id' => '9','survey_id' => '2'],
                    ['template_id' => '16','survey_id' => '13'],
                    ['template_id' => '15','survey_id' => '4'],
                    ['template_id' => '17','survey_id' => '6'],
                    ['template_id' => '18','survey_id' => '7'],
                    ['template_id' => '19','survey_id' => '8'],
                    ['template_id' => '22','survey_id' => '11'],
                    ['template_id' => '26','survey_id' => '12'],
                    ['template_id' => '25','survey_id' => '10']
                    
                ];

        $table = $this->table('template_surveys');
        $table->insert($data)->save();
    }
}
