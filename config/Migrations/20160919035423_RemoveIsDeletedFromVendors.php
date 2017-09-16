<?php
use Migrations\AbstractMigration;

class RemoveIsDeletedFromVendors extends AbstractMigration
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
        $table->removeColumn('is_deleted');
        $table->update();
    }
}
