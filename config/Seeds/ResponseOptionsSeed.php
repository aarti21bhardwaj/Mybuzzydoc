<?php
use Migrations\AbstractSeed;

/**
 * ResponseOptions seed.
 */
class ResponseOptionsSeed extends AbstractSeed
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
                      'response_group_id' =>1,
                      'name'    => 'yes',
                      'label'   => 'Yes',
                      'weightage'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
                      [
                      'response_group_id' =>1,
                      'name'    => 'no',
                      'label'   => 'No',
                      'weightage'=> 0,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
                      [
                      'response_group_id' =>2,
                      'name'    => 'patient_doing_great',
                      'label'   => 'Patient is doing Great!',
                      'weightage'=> 0,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
                      [
                      'response_group_id' =>2,
                      'name'    => 'no_change',
                      'label'   => 'Patient has not improved or declined',
                      'weightage'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
                      [
                      'response_group_id' =>2,
                      'name'    => 'patient_gotten_worse',
                      'label'   => 'Patient has gotten worse',
                      'weightage'=> 2,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      ],
        ];

        $table = $this->table('response_options');
        $table->insert($data)->save();
    }
}
