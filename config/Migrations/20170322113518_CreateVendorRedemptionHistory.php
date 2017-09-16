<?php
use Migrations\AbstractMigration;

class CreateVendorRedemptionHistory extends AbstractMigration
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
        $table = $this->table('vendor_redemption_history');
        $table->addColumn('vendor_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('actual_balance', 'float', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('redeemed_amount', 'float', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('remaining_amount', 'float', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('cc_charged_amount', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('cc_transaction_identifier', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
