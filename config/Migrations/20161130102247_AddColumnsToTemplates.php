<?php
use Migrations\AbstractMigration;

class AddColumnsToTemplates extends AbstractMigration
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
        $table = $this->table('templates');
        $table->addColumn('milestone', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('gift_coupon', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
