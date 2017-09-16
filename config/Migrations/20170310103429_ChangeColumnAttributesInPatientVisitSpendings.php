<?php
use Migrations\AbstractMigration;

class ChangeColumnAttributesInPatientVisitSpendings extends AbstractMigration
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
        $table = $this->table('patient_visit_spendings');
        $table->changeColumn('token', 'string', array('null' => true))
              ->changeColumn('is_deleted', 'datetime', array('null' => true));
        $table->update();
    }
}
