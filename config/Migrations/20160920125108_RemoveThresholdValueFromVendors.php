<?php
use Migrations\AbstractMigration;

class RemoveThresholdValueFromVendors extends AbstractMigration
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
        $table = $this->table('vendors');
        $table->removeColumn('threshold_value');
        $table->update();
    }
}
