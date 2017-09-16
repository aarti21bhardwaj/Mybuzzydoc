<?php
use Migrations\AbstractMigration;

class ChangeReviewAwards extends AbstractMigration
{
    public function up(){
        $reviewAwards = $this->table('review_awards');
        $reviewAwards->changeColumn('user_id', 'integer', [
                        'default' => null,
                        'limit' => 11,
                        'null' => true,
                    ])
                    ->save();
    }
}
