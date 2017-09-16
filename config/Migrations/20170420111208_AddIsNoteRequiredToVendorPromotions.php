<?php
use Migrations\AbstractMigration;

class AddIsNoteRequiredToVendorPromotions extends AbstractMigration
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
        $table = $this->table('vendor_promotions');
        $table->addColumn('is_note_required', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
