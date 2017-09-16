<?php
use Migrations\AbstractSeed;

/**
 * Referral Template seed.
 */
class ReferralTemplatesSeed extends AbstractSeed
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
                      [
                      'id'       => 1,
                      'vendor_id' => 2,
                      'subject' => 'Basic',
                      'description' => 'This is Basic template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 2,
                      'vendor_id' => 2,
                      'subject' => 'Level 1',
                      'description' => 'This is level 1 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 3,
                      'vendor_id' => 2,
                      'subject' => 'Level 2',
                      'description' => 'This is level 2 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 4,
                      'vendor_id' => 1,
                      'subject' => 'Level 2',
                      'description' => 'This is level 2 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 5,
                      'vendor_id' => 1,
                      'subject' => 'Level 1',
                      'description' => 'This is level 1 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 6,
                      'vendor_id' => 3,
                      'subject' => 'Level 1',
                      'description' => 'This is level 1 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 7,
                      'vendor_id' => 3,
                      'subject' => 'Level 2',
                      'description' => 'This is level 2 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 8,
                      'vendor_id' => 4,
                      'subject' => 'Level 1',
                      'description' => 'This is level 1 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
                      [
                      'id'       => 9,
                      'vendor_id' => 4,
                      'subject' => 'Level 2',
                      'description' => 'This is level 2 template',
                      'status' => 1,
                      'is_deleted' => null,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                      ],
              
                ];

        $table = $this->table('referral_templates');
        $table->insert($data)->save();
    }
}
