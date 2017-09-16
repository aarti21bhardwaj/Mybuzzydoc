<?php
use Migrations\AbstractMigration;

class ChangeColumnTypeInMyAudits extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('my_audits')
                      ->changeColumn('original', 'text', [
                            'default' => null,
                            'null' => true,
                        ])
                      ->changeColumn('changed', 'text', [
                            'default' => null,
                            'null' => true,
                        ])
                      ->changeColumn('meta', 'text', [
                            'default' => null,
                            'null' => true,
                        ])
                      ->update();
    }
    public function down()
    {
        $table = $this->table('my_audits');
        $table->addColumn('original', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('changed', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('meta', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
