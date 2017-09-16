<?php
use Migrations\AbstractSeed;

/**
 * Promotions seed.
 */
class PromotionsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    ['id' => '1','name' => 'For Check In','description' => 'For Check In','points' => '50','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '2'],
                    ['id' => '2','name' => 'Patient Referral (50]','description' => 'For Referral','points' => '50','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '3'],
                    ['id' => '3','name' => 'For Review','description' => 'For Review','points' => '50','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '4'],
                    ['id' => '4','name' => 'For Social','description' => 'For Social','points' => '50','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '2'],
                    ['id' => '5','name' => 'User liked the practice on Facebook','description' => 'User liked the practice on Facebook','points' => '0','vendor_id' => '1','is_deleted' => '2016-12-30 07:26:34','status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '10'],
                    ['id' => '6','name' => 'User liked the practice on Facebook','description' => 'User liked the practice on Facebook','points' => '100','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '7','name' => 'Straight As on report card for 8 classes','description' => 'Patient gets straight As on report card for 8 classes.','points' => '400','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => NULL],
                    ['id' => '8','name' => 'Community Service for 5 hrs','description' => 'Community Service for 5 hrs','points' => '500','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '9','name' => 'Appointment on Birthday','description' => 'Appointment on Birthday','points' => '100','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '356'],
                    ['id' => '10','name' => 'Wearing Office T-Shirt','description' => 'Wearing Office T-Shirt','points' => '50','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '11','name' => 'Patient Referral (2500]','description' => 'Patient Referral','points' => '2500','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '12','name' => 'Patient Referral (1000]','description' => 'For referring a patient.','points' => '1000','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '0'],
                    ['id' => '13','name' => 'Patient Referral (1250]','description' => 'For referral','points' => '1250','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '14','name' => 'Patient Referral (5000]','description' => 'For referral','points' => '5000','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '15','name' => 'Patient Referral (2000]','description' => 'For referral','points' => '2000','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1'],
                    ['id' => '16','name' => 'Wearing office T-shirt (100]','description' => 'Patient wears the office T-shirt during the appointment.','points' => '100','vendor_id' => '1','is_deleted' => NULL,'status' => '1','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s'),'frequency' => '1']
        ];

        $table = $this->table('promotions');
        $table->insert($data)->save();
    }
}
