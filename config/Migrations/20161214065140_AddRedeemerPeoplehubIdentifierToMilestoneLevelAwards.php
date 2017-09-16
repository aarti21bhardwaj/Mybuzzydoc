<?php
use Migrations\AbstractMigration;

class AddRedeemerPeoplehubIdentifierToMilestoneLevelAwards extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('milestone_level_awards');
        $table->addColumn('redeemer_peoplehub_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }

    public function down(){
        $table = $this->table('milestone_level_awards');
        $table->removeColumn('redeemer_peoplehub_identifier')
              ->update();
    }
}

