<?php
use Migrations\AbstractSeed;

/**
 * EmailTemplates seed.
 */
class EmailTemplatesSeed extends AbstractSeed
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
                    'name' => 'default',
                    'vendor_id'=> 1,

                ],
        ];

        $table = $this->table('email_templates');
        $table->insert($data)->save();
    }
}
