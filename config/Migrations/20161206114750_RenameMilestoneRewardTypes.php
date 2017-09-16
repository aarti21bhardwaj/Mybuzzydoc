<?php
use Migrations\AbstractMigration;

class RenameMilestoneRewardTypes extends AbstractMigration
{
    public function up(){

        $this->table('milestone_reward_types')
            ->rename('reward_types');
    }

}
