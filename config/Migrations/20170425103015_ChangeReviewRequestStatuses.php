<?php
use Migrations\AbstractMigration;

class ChangeReviewRequestStatuses extends AbstractMigration
{
    public function up(){
        $reviewRequestStatuses = $this->table('review_request_statuses');
        $reviewRequestStatuses->changeColumn('user_id', 'integer', [
                        'default' => null,
                        'limit' => 11,
                        'null' => true,
                    ])

                  ->save();
    }
}
