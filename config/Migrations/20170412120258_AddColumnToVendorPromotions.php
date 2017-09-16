<?php
use Migrations\AbstractMigration;

class AddColumnToVendorPromotions extends AbstractMigration
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
        $table = $this->table('vendor_promotions');
        $table->addColumn('patient_portal_status', 'boolean', [
            'default' => 1,
            'null' => false,
        ]);
        $table->update();
    }
}
