<?php
use Migrations\AbstractMigration;

class ChangeColumnVendorReferralSettingsId extends AbstractMigration
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
        $table = $this->table('referral_leads');
        $table->changeColumn('vendor_referral_settings_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->save();
    }
}
