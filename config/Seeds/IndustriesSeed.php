<?php
use Migrations\AbstractSeed;

/**
 * Industries seed.
 */
class IndustriesSeed extends AbstractSeed
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
                'name' => 'Audiology' 
            ],
            [
                'name' => 'General Dentistry' 
            ],
            [
                'name' => 'Medical Aesthetics' 
            ],
            [
                'name' => 'Optometry' 
            ],
            [
                'name' => 'Orthodontics' 
            ],
            [
                'name' => 'Veterinary' 
            ]


        ];

        $table = $this->table('industries');
        $table->insert($data)->save();
    }
}
