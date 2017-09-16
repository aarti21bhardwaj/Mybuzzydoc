<?php
use Migrations\AbstractMigration;

class AddYahooToReviewRequestStatuses extends AbstractMigration
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
        $table = $this->table('review_request_statuses');
        $table->addColumn('yahoo', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
