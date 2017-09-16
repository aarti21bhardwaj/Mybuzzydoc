<?php
use Migrations\AbstractMigration;

class ChangeAutoSignoutDefault extends AbstractMigration
{
    
    public function up(){

        $users = $this->table('users');
        $users->changeColumn('idle_timer', 'boolean', array('default' => 0))
              ->save();
    }
    
}