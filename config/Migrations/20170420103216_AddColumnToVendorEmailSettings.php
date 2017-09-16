<?php
use Migrations\AbstractMigration;

class AddColumnToVendorEmailSettings extends AbstractMigration
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
        $table = $this->table('vendor_email_settings');
        $table->addColumn('status', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->update();
    }
}
