<?php
use Migrations\AbstractSeed;

/**
 * SurveyTypes seed.
 */
class SurveyTypesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [

                    [
                      'name'    => 'Staff Assessment',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ],
                    [
                      'name'    => 'Patient Reported Outcomes',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                    ],
        ];

        $table = $this->table('survey_types');
        $table->insert($data)->save();
    }
}
