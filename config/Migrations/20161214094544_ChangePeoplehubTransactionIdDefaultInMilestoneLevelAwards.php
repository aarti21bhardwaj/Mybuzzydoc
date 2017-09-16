<?php
use Migrations\AbstractMigration;

class ChangePeoplehubTransactionIdDefaultInMilestoneLevelAwards extends AbstractMigration
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
        $table = $this->table('milestone_level_awards');
        $table->changeColumn('peoplehub_transaction_id', 'integer',array('null' => true))
              ->update();
    }
}