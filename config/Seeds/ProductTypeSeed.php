<?php
use Migrations\AbstractSeed;

/**
 * ProductType seed.
 */
class ProductTypeSeed extends AbstractSeed
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
                       'name'    => 'Amazon/Tango',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'In-House',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'e-gift card',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Toys & Games',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Clothing & Accessories',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'House & Home',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Gift Cards',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Electronics',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Sports & Outdoors',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Health & Beauty',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Arts & Crafts',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Books, Music & Movies',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
                      [
                       'name'    => 'Services',
                      'status'=> 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified'=> date('Y-m-d H:i:s'),
                      'is_deleted' => 0,
                      ],
        ];

        $table = $this->table('product_types');
        $table->insert($data)->save();
    }
}
