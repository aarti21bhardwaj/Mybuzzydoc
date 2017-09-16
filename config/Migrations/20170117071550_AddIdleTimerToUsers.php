<?php
use Migrations\AbstractMigration;

class AddIdleTimerToUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('idle_timer', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->update();
    }
}
