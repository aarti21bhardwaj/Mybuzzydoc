<?php
use Migrations\AbstractSeed;

/**
 * ResponseGroups seed.
 */
class ResponseGroupsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                     [
                      'name'    => 'Two Points Scale',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
                      [
                      'name'    => 'Assessment Three Points Scale',
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ]
        ];

        $table = $this->table('response_groups');
        $table->insert($data)->save();
    }
}
