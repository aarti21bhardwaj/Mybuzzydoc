<?php
use Migrations\AbstractMigration;

class ChangeReferralLeads extends AbstractMigration
{
    public function up(){

        $referralLeads = $this->table('referral_leads');
        $referralLeads->removeColumn('name')
                  ->removeColumn('status')
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
                  ->addColumn('parent_name', 'string', [
                        
                        'default' => null,
                        'limit' => 255,
                        'null' => true,
                    ])
                  ->addColumn('notes', 'text', [
                        
                        'default' => null,
                        'null' => true
                    ])
                  ->addColumn('peoplehub_identifier', 'integer', [
                        
                        'default' => null,
                        'limit' => 11,
                        'null' => true,
                    ])
                  ->addColumn('referral_status_id', 'integer', [

                        'default' => null,
                        'limit' => 11,
                        'null' => false,
                    ])
                  ->changeColumn('email', 'string', [

                        'default' => null,
                        'limit' => 255,
                        'null' => true,

                    ])
                  ->changeColumn('phone', 'string', [

                        'default' => null,
                        'limit' => 255,
                        'null' => true,

                    ])
                  ->save();
    }
}
