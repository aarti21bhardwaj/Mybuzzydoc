<?php
use Migrations\AbstractMigration;

class ChangeColumnsInSurveyAwards extends AbstractMigration
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

        $table = $this->table('survey_awards');
        $table->renameColumn('total_points', 'points')
              ->addColumn('user_id', 'integer', [
                          'default' => null,
                          'limit' => 11,
                          'null' => false,
                         ])
              ->addColumn('peoplehub_transaction_id', 'integer', [
                          'default' => null,
                          'limit' => 11,
                          'null' => false,
                         ])
              ->renameColumn('survey_instance_id', 'vendor_survey_instance_id');
        $table->update();
    }
}
