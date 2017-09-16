<?php
use Migrations\AbstractMigration;

class ChangePointsTypeInLegacyRewards extends AbstractMigration
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
        $table = $this->table('legacy_rewards');
        $table->changeColumn('points', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ])->save();
    }
    public function down()
    {
        $table = $this->table('legacy_rewards');
        $table->changeColumn('points', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])->save();
    }
}
