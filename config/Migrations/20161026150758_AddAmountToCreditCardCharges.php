<?php
use Migrations\AbstractMigration;

class AddAmountToCreditCardCharges extends AbstractMigration
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
        $table = $this->table('credit_card_charges');
        $table->addColumn('amount', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
