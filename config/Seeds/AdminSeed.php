<?php
use Phinx\Seed\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Text;
/**
 * Roles seed.
 */
class AdminSeed extends AbstractSeed
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
      $hasher = new DefaultPasswordHasher();
        $data = [
                    [
                      'vendor_id' => 1,
                      'first_name'    => 'admin',
                      'last_name'    => 'admin',
                      'username' => 'admin',
                      'email'   =>'nikhil@twinspark.co',
                      'password'   =>$hasher->hash('12345678'),
                      'uuid'=>Text::uuid(),
                      'phone'=> '9999999999',
                      'role_id'=>'1',
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'vendor_location_id' => 0
                      ],
                      [
                      'vendor_id' => 2,
                      'first_name'    => 'Charizad',
                      'last_name'    => 'Charizad',
                      'username' => 'charizad',
                      'email'   =>'charizad@twinspark.co',
                      'password'   =>$hasher->hash('12345678'),
                      'uuid'=>Text::uuid(),
                      'phone'=> '1(800)233-2742',
                      'role_id'=>'2',
                      'created' => '2016-08-15 10:01:27',
                      'modified'=> '2016-08-15 10:01:27',
                      'vendor_location_id' => 0
                      ],
                      [
                      'vendor_id' => 3,
                      'first_name'    => 'Blastoise',
                      'last_name'    => 'Blastoise',
                      'username' => 'blastoise',
                      'email'   =>'blastoise@twinspark.co',
                      'password'   =>$hasher->hash('12345678'),
                      'uuid'=>Text::uuid(),
                      'phone'=> '1(800)233-2743',
                      'role_id'=>'2',
                      'created' => '2016-9-15 10:01:27',
                      'modified'=> '2016-9-15 10:01:27',
                      'vendor_location_id' => 0
                      ],
                      [
                      'vendor_id' => 4,
                      'first_name'    => 'Venasaur',
                      'last_name'    => 'Venasaur',
                      'username' => 'venasaur',
                      'email'   =>'venasaur@twinspark.co',
                      'password'   =>$hasher->hash('12345678'),
                      'uuid'=>Text::uuid(),
                      'phone'=> '1(800)233-2744',
                      'role_id'=>'2',
                      'created' => '2016-07-15 10:01:27',
                      'modified'=> '2016-07-15 10:01:27',
                      'vendor_location_id' => 0
                      ]


        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
