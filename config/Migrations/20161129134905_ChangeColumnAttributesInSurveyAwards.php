<?php
use Migrations\AbstractMigration;

class ChangeColumnAttributesInSurveyAwards extends AbstractMigration
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
        $table = $this->table('survey_awards');
        $table->changeColumn('peoplehub_transaction_id', 'integer', ['null' => true])
              ->save();
    }

    public function down()
    {
        $table = $this->table('survey_awards');
        $table->changeColumn('peoplehub_transaction_id', 'integer', ['null' => false])
              ->save();
    }
}
