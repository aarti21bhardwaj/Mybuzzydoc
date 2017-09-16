<?php
use Migrations\AbstractSeed;

/**
 * Surveys seed.
 */
class SurveysSeed extends AbstractSeed
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

                    ['name' => 'Orthodontics Compliance Survey'],
                    ['name' => 'Plastics Better Compliance survey'],
                    ['name' => 'Optometry Better Compliance survey'],
                    ['name' => 'Veterinary Better Compliance Survey'],
                    ['name' => 'Audiology Better Compliance Survey'],
                    ['name' => 'Orthodontics Better Compliance Survey (Aggressive]'],
                    ['name' => 'General Dentistry Better Compliance Survey (Aggressive]'],
                    ['name' => 'Plastics Better Compliance survey (Aggressive]'],
                    ['name' => 'Optometry Better Compliance survey (Aggressive]'],
                    ['name' => 'Veterinary Better Compliance Survey (Aggressive]'],
                    ['name' => 'Audiology Better Compliance Survey (Aggressive]'],
                    ['name' => 'General Dentistry Best Compliance Survey (Aggressive]'],
                    ['name' => 'General Dentistry (Better]']        
                ];

        $table = $this->table('surveys');
        $table->insert($data)->save();
    }
}
