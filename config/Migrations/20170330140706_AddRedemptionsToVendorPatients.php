<?php
use Migrations\AbstractMigration;

class AddRedemptionsToVendorPatients extends AbstractMigration
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
        $table->addColumn('redemptions', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->update();
    }
}