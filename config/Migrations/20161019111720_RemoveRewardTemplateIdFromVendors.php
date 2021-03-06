<?php
use Migrations\AbstractMigration;

class RemoveRewardTemplateIdFromVendors extends AbstractMigration
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
        $table->removeColumn('reward_template_id');
        $table->update();
    }
}
