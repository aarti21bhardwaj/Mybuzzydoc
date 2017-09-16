<?php
use Migrations\AbstractMigration;

class ChangeReferrals extends AbstractMigration
{
    public function up(){

        $referrals = $this->table('referrals');
        $referrals->removeColumn('name')
                  ->addColumn('first_name', 'string', [
                        
                        'default' => null,
                        'limit' => 255,
                        'null' => false,
                    ])
                  ->addColumn('last_name', 'string', [
                        
                        'default' => null,
                        'limit' => 255,
                        'null' => true,
                    ])
                  ->changeColumn('refer_to', 'string', [

                        'default' => null,
                        'limit' => 255,
                        'null' => true,

                    ])

                  ->save();
    }
}