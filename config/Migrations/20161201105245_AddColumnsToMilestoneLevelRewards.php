<?php
use Migrations\AbstractMigration;

class AddColumnsToMilestoneLevelRewards extends AbstractMigration
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
        $table = $this->table('milestone_level_rewards');

        $table->addColumn('reward_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
       
        $table->update();
    }
}
