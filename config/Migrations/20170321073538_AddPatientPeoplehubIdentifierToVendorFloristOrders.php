<?php
use Migrations\AbstractMigration;

class AddPatientPeoplehubIdentifierToVendorFloristOrders extends AbstractMigration
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
        $table = $this->table('vendor_florist_orders');
        $table->addColumn('patient_peoplehub_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
