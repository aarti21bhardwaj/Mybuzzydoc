<?php
use Migrations\AbstractMigration;

class CreateVendors extends AbstractMigration
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
        $table->addColumn('org_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
        
        $table->addColumn('reward_url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            ]);
        $table->addColumn('reward_template', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            ]);
        $table->addColumn('min_deposit', 'integer', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
         $table->addColumn('threshold_value', 'integer', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            ]);
         $table->addColumn('is_legacy', 'boolean', [
            'default' => 1,
            'limit' => 255,
            'null' => false,
            ]);
        
        $table->addColumn('people_hub_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
            ]);
        $table->addColumn('status', 'boolean', [
            'default' => 1,
            'limit' => null,
            'null' => true,
            ]);
        $table->addColumn('is_deleted', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
            ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
            ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
            ]);
        $table->create();
    }
}
