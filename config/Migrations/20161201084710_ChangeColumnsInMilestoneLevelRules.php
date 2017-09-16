<?php
use Migrations\AbstractMigration;

class ChangeColumnsInMilestoneLevelRules extends AbstractMigration
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
         $table = $this->table('milestone_level_rules');
        $table->renameColumn('condition', 'level_rule');
        
        $table->update();
    }
    
}

