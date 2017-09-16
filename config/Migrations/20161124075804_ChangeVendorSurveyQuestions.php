<?php
use Migrations\AbstractMigration;

class ChangeVendorSurveyQuestions extends AbstractMigration
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
        $table = $this->table('vendor_survey_questions');
        $table->renameColumn('vendor_id', 'vendor_survey_id')
              ->update();
    }
}
