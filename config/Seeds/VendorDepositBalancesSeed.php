<?php
use Migrations\AbstractSeed;

/**
 * VendorDepositBalances seed.
 */
class VendorDepositBalancesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
        [
            'vendor_id' =>'2',
            'balance' => '3000',
            'created' => date('Y-m-d H:i:s'),
            'modified'=> date('Y-m-d H:i:s'),
        ],
        ];

        $table = $this->table('vendor_deposit_balances');
        $table->insert($data)->save();
    }
}
