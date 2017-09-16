<?php
use Migrations\AbstractMigration;

class AddPeopleHubIdToReviewRequestStatuses extends AbstractMigration
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
        $table = $this->table('review_request_statuses');
        $table->addColumn('people_hub_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('email_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }

    public function down(){
        $table = $this->table('review_request_statuses');
        $table->removeColumn('people_hub_identifier')
              ->removeColumn('email_address')
              ->update();
    }
}
