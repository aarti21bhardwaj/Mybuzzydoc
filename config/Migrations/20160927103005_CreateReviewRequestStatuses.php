<?php
use Migrations\AbstractMigration;

class CreateReviewRequestStatuses extends AbstractMigration
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
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('vendor_review_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('rating', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('review', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('fb', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('google_plus', 'boolean', [
           'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('yelp', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('ratemd', 'boolean', [
            'default' => 0,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('healthgrades', 'boolean', [
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
