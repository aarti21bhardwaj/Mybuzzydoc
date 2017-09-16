<?php
use Migrations\AbstractMigration;

class AddRedeemerPeoplehubIdentifierToReferralAwards extends AbstractMigration
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
        $table = $this->table('referral_awards');
        $table->addColumn('redeemer_peoplehub_identifier', 'integer',
                            [
                                'default' => null,
                                'limit' => 11,
                                'null' => true,
                            ])
              ->addColumn('vendor_id', 'integer', 
                            [
                                'default' => null,
                                'limit' => 11,
                                'null' => false,
                            ])
              ->update();
    }
}
