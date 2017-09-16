<?php
use Migrations\AbstractSeed;

/**
 * SurveyQuestions seed.
 */
class SurveyQuestionsSeed extends AbstractSeed
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
                      ['survey_id' => '1','question_id' => '1'],
                      ['survey_id' => '1','question_id' => '2'],
                      ['survey_id' => '1','question_id' => '3'],
                      ['survey_id' => '1','question_id' => '4'],
                      ['survey_id' => '1','question_id' => '5'],
                      ['survey_id' => '2','question_id' => '1'],
                      ['survey_id' => '2','question_id' => '6'],
                      ['survey_id' => '2','question_id' => '7'],
                      ['survey_id' => '2','question_id' => '8'],
                      ['survey_id' => '2','question_id' => '9'],
                      ['survey_id' => '3','question_id' => '1'],
                      ['survey_id' => '3','question_id' => '10'],
                      ['survey_id' => '3','question_id' => '11'],
                      ['survey_id' => '3','question_id' => '12'],
                      ['survey_id' => '3','question_id' => '13'],
                      ['survey_id' => '4','question_id' => '1'],
                      ['survey_id' => '4','question_id' => '14'],
                      ['survey_id' => '4','question_id' => '15'],
                      ['survey_id' => '4','question_id' => '16'],
                      ['survey_id' => '4','question_id' => '17'],
                      ['survey_id' => '5','question_id' => '1'],
                      ['survey_id' => '5','question_id' => '18'],
                      ['survey_id' => '5','question_id' => '11'],
                      ['survey_id' => '5','question_id' => '19'],
                      ['survey_id' => '5','question_id' => '20'],
                      ['survey_id' => '5','question_id' => '13'],
                      ['survey_id' => '6','question_id' => '21'],
                      ['survey_id' => '6','question_id' => '22'],
                      ['survey_id' => '6','question_id' => '23'],
                      ['survey_id' => '6','question_id' => '24'],
                      ['survey_id' => '6','question_id' => '25'],
                      ['survey_id' => '7','question_id' => '21'],
                      ['survey_id' => '7','question_id' => '26'],
                      ['survey_id' => '7','question_id' => '27'],
                      ['survey_id' => '7','question_id' => '28'],
                      ['survey_id' => '7','question_id' => '25'],
                      ['survey_id' => '8','question_id' => '21'],
                      ['survey_id' => '8','question_id' => '6'],
                      ['survey_id' => '8','question_id' => '7'],
                      ['survey_id' => '8','question_id' => '8'],
                      ['survey_id' => '8','question_id' => '9'],
                      ['survey_id' => '9','question_id' => '21'],
                      ['survey_id' => '9','question_id' => '29'],
                      ['survey_id' => '9','question_id' => '30'],
                      ['survey_id' => '9','question_id' => '31'],
                      ['survey_id' => '9','question_id' => '32'],
                      ['survey_id' => '10','question_id' => '21'],
                      ['survey_id' => '10','question_id' => '33'],
                      ['survey_id' => '10','question_id' => '34'],
                      ['survey_id' => '10','question_id' => '35'],
                      ['survey_id' => '10','question_id' => '36'],
                      ['survey_id' => '11','question_id' => '21'],
                      ['survey_id' => '11','question_id' => '18'],
                      ['survey_id' => '11','question_id' => '37'],
                      ['survey_id' => '11','question_id' => '38'],
                      ['survey_id' => '11','question_id' => '39'],
                      ['survey_id' => '11','question_id' => '40'],
                      ['survey_id' => '12','question_id' => '41'],
                      ['survey_id' => '12','question_id' => '42'],
                      ['survey_id' => '12','question_id' => '43'],
                      ['survey_id' => '12','question_id' => '44'],
                      ['survey_id' => '12','question_id' => '36'],
                      ['survey_id' => '13','question_id' => '46'],
                      ['survey_id' => '13','question_id' => '47'],
                      ['survey_id' => '13','question_id' => '48'],
                      ['survey_id' => '13','question_id' => '49'],
                      ['survey_id' => '13','question_id' => '50']
        ];

        $table = $this->table('survey_questions');
        $table->insert($data)->save();
    }
}
