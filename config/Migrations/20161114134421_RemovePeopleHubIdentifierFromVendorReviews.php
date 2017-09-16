<?php
use Migrations\AbstractMigration;

class RemovePeopleHubIdentifierFromVendorReviews extends AbstractMigration
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
        $table = $this->table('vendor_reviews');
        $table->removeColumn('people_hub_identifier')
              ->update();
    }

    public function down(){
        $table = $this->table('vendor_reviews');
        $table->addColumn('people_hub_identifier', 'integer', [
                            'default' => null,
                            'limit' => 11,
                            'null' => false,
                        ])
              ->update();   
    }

}
