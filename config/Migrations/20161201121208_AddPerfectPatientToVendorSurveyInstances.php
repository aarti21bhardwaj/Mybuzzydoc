<?php
use Migrations\AbstractMigration;

class AddPerfectPatientToVendorSurveyInstances extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('vendor_survey_instances');
        $table->addColumn('perfect_patient', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
    public function down()
    {
        $table = $this->table('vendor_survey_instances')
                      ->removeColumn('perfect_patient')
                      ->update();
    }
}
