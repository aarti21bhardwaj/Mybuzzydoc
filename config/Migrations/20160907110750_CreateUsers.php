<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $table = $this->table('users');
         $table->addColumn('vendor_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]);
        $table->addColumn('first_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
        $table->addColumn('last_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            ]);
        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
            ]);
        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            ]);
        $table->addColumn('role_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
            ]);
        $table->addColumn('uuid', 'uuid', [
            'default' => null,
            'null' => false,
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
