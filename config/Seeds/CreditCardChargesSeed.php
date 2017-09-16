<?php
use Migrations\AbstractSeed;

/**
 * CreditCardCharges seed.
 */
class CreditCardChargesSeed extends AbstractSeed
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
            'user_id' =>'2',
            'vendor_id' =>'2',
            'auth_code' =>'43T3BX',
            'transactionid' => '40004198097',
            'description' => 'This transaction has been approved.',
            'response_code' => 1,
            'created' => date('Y-m-d H:i:s'),
            'modified'=> date('Y-m-d H:i:s'),
            'reason' => '2016-11-11 00:00:00 is charged via C.C.',
            'amount' => '3000',
            'transaction_fee' => '30',
        ],
        ];

        $table = $this->table('credit_card_charges');
        $table->insert($data)->save();
    }
}
