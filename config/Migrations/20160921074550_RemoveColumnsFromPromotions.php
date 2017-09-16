<?php
use Migrations\AbstractMigration;

class RemoveColumnsFromPromotions extends AbstractMigration
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
        $table = $this->table('promotions');
        $table->removeColumn('status')
              ->removeColumn('created')
              ->removeColumn('modified')
              ->save();
    }
    public function down()
    {
        $table = $this->table('promotions');
        $table->addColumn('status')
              ->addColumn('created')
              ->addColumn('modified')
              ->save();
    }

}
