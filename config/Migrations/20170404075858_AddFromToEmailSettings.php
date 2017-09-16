<?php
use Migrations\AbstractMigration;

class AddFromToEmailSettings extends AbstractMigration
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
        $table = $this->table('email_settings');
        $table->addColumn('from_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('recipients', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
