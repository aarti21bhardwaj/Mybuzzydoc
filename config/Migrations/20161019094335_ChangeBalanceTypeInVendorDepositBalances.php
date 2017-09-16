<?php
use Migrations\AbstractMigration;

class ChangeBalanceTypeInVendorDepositBalances extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('vendor_deposit_balances');
        $table->changeColumn('balance', 'float', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])->save();
    }
    public function down()
    {
        $table = $this->table('vendor_deposit_balances');
        $table->changeColumn('balance', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])->save();
    }
}
