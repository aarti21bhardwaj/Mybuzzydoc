<?php
use Migrations\AbstractMigration;

class ChangeColumnTypeAmountinLegacyRewards extends AbstractMigration
{
    public function up()
    {
        $legacyRewards = $this->table('legacy_rewards');
        $legacyRewards->changeColumn('amount', 'float',array('null' => true))
                         ->save();
    }
    public function down(){
        $legacyRewards = $this->table('legacy_rewards');
        $legacyRewards->changeColumn('amount', 'integer',array('null' => false))
                         ->save(); 
    }
}
