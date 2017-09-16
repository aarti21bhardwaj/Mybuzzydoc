<?php
use Migrations\AbstractMigration;

class AddThresholdValueToVendors extends AbstractMigration
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
        $table->addColumn('threshold_value', 'float', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->update();
    }
}
