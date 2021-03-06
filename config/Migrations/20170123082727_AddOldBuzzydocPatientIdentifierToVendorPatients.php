<?php
use Migrations\AbstractMigration;

class AddOldBuzzydocPatientIdentifierToVendorPatients extends AbstractMigration
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
        $table = $this->table('vendor_patients');
        $table->addColumn('old_buzzydoc_patient_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->update();
    }
}
