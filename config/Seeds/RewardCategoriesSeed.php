<?php
use Migrations\AbstractSeed;

/**
 * RewardCategories seed.
 */
class RewardCategoriesSeed extends AbstractSeed
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
        $data = [     [
                       'name'    => 'Product',
                      'label'   =>'Product',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [ 'name'    => 'Service',
                      'label'   =>'Service',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [ 'name'    => 'Legacy',
                      'label'   =>'Legacy',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ]
        ];

        $table = $this->table('reward_categories');
        $table->insert($data)->save();
    }
}
