<?php
use Migrations\AbstractMigration;

class AddIsCardSetupToAuthorizeNetProfiles extends AbstractMigration
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
        $table = $this->table('authorize_net_profiles');
        $table->addColumn('is_card_setup', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
