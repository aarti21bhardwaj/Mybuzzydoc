<?php
use Migrations\AbstractMigration;

class UpdateReviewAwards extends AbstractMigration
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
        $table = $this->table('review_awards');
        $table->addColumn('review_request_status_id', 'integer', [
                                'default' => null,
                                'limit' => 11,
                                'null' => false,
                            ])
              ->removeColumn('vendor_review_id')
              ->update();
    }

    public function down(){
        $table = $this->table('review_awards');
        $table->addColumn('vendor_review_id', 'integer', [
                            'default' => null,
                            'limit' => 11,
                            'null' => false,
                        ])
              ->removeColumn('review_request_status_id')
              ->update();

    }
}
