<?php
use Migrations\AbstractMigration;

class AddColumnWelcomeMessageToVendors extends AbstractMigration
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
        $table->addColumn('welcome_message', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
        ]);
        $table->update();
    }
}
