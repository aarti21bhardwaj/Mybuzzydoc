<?php
use Migrations\AbstractMigration;

class AddUuidToAssessmentSurveyInstances extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('assessment_survey_instances');
        $table->addColumn('uuid', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
