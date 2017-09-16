<?php
use Migrations\AbstractMigration;

class CreateVendorFloristTransactions extends AbstractMigration
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
        $table = $this->table('vendor_florist_transactions');
        $table->addColumn('vendor_florist_order_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('florist_transaction_id', 'integer', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]);
        $table->create();
    }
}
