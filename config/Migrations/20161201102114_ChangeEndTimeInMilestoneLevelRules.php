<?php
use Migrations\AbstractMigration;

class ChangeEndTimeInMilestoneLevelRules extends AbstractMigration
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
        $milestoneLevelRules = $this->table('milestone_level_rules');
        $milestoneLevelRules->changeColumn('end_time', 'datetime',array('null' => true))
              ->save();
    }
    public function down(){
        $milestoneLevelRules = $this->table('milestone_level_rules');
        $milestoneLevelRules->changeColumn('end_time', 'datetime',array('null' => false))
              ->save(); 
    }
    
}
