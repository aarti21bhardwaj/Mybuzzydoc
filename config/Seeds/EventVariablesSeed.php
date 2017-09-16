<?php
use Migrations\AbstractSeed;

/**
 * Events seed.
 */
class EventVariablesSeed extends AbstractSeed
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
                'id'=> 1,
                'event_id' => 1,
                'name'=> 'First Name',
                'description' => 'Demo',
                'key' => 'first_name'
            ],
            [
                'id'=> 2,
                'event_id' => 1,
                'name'=> 'Username',
                'description' => 'Demo',
                'key' => 'username'
            ],
            [
                'id'=> 3,
                'event_id' => 1,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 4,
                'event_id' => 2,
                'name'=> 'Address',
                'description' => 'Demo',
                'key' => 'address'
            ],
            [
                'id'=> 5,
                'event_id' => 2,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 6,
                'event_id' => 3,
                'name'=> 'First Name',
                'description' => 'Demo',
                'key' => 'first_name'
            ],
            [
                'id'=> 7,
                'event_id' => 3,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 8,
                'event_id' => 4,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 9,
                'event_id' => 5,
                'name'=> 'Name',
                'description' => 'Demo',
                'key' => 'name'
            ],
            [
                'id'=> 10,
                'event_id' => 6,
                'name'=> 'Name',
                'description' => 'Demo',
                'key' => 'name'
            ],
            [
                'id'=> 11,
                'event_id' => 9,
                'name'=> 'Name',
                'description' => 'Demo',
                'key' => 'User Name'
            ],
            [
                'id'=> 12,
                'event_id' => 2,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 13,
                'event_id' => 2,
                'name'=> 'Clinic Name',
                'description' => 'Demo',
                'key' => 'clinic_name'
            ],
            [
                'id'=> 14,
                'event_id' => 2,
                'name'=> 'Points',
                'description' => 'Demo',
                'key' => 'points'

            ],
            [
                'id'=> 15,
                'event_id' => 9,
                'name'=> 'Password',
                'description' => 'Demo',
                'key' => 'password'

            ],
            [
                'id'=> 16,
                'event_id' => 9,
                'name'=> 'Username',
                'description' => 'Demo',
                'key' => 'username'
            ],
            [
                'id'=> 17,
                'event_id' => 4,
                'name'=> 'First Name',
                'description' => 'Demo',
                'key' => 'first_name'
            ],
            [
                'id'=> 18,
                'event_id' => 4,
                'name'=> 'Last Name',
                'description' => 'Demo',
                'key' => 'last_name'
            ],
            [
                'id'=> 19,
                'event_id' => 10,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 20,
                'event_id' => 10,
                'name'=> 'Email',
                'description' => 'Demo',
                'key' => 'email'
            ],
            [
                'id'=> 21,
                'event_id' => 10,
                'name'=> 'Vendor Name',
                'description' => 'Demo',
                'key' => 'org_name'
            ],
            [
                'id'=> 22,
                'event_id' => 10,
                'name'=> 'Reset Link',
                'description' => 'Demo',
                'key' => 'reset_link'
            ],
            [
                'id'=> 23,
                'event_id' => 10,
                'name'=> 'Patient Portal Link',
                'description' => 'Demo',
                'key' => 'ref'
            ],
            [
                'id'=> 24,
                'event_id' => 10,
                'name'=> 'Patient Username',
                'description' => 'Demo',
                'key' => 'username'
            ],
            [
                'id'=> 25,
                'event_id' => 11,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 26,
                'event_id' => 11,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 27,
                'event_id' => 11,
                'name'=> 'Organization Name',
                'description' => 'Demo',
                'key' => 'org_name'
            ],
            [
                'id'=> 28,
                'event_id' => 12,
                'name'=> 'Practice Name',
                'description' => 'Demo',
                'key' => 'practice_name'
            ],
            [
                'id'=> 29,
                'event_id' => 12,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 30,
                'event_id' => 12,
                'name'=> 'Patient Email',
                'description' => 'Demo',
                'key' => 'email'
            ],
            [
                'id'=> 31,
                'event_id' => 13,
                'name'=> 'Practice Name',
                'description' => 'Demo',
                'key' => 'practice_name'
            ],
            [
                'id'=> 32,
                'event_id' => 13,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 33,
                'event_id' => 13,
                'name'=> 'Redemption Type',
                'description' => 'Demo',
                'key' => 'redemption_type'
            ],
            [
                'id'=> 34,
                'event_id' => 13,
                'name'=> 'Points',
                'description' => 'Demo',
                'key' => 'points'
            ],
            [
                'id'=> 35,
                'event_id' => 14,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 36,
                'event_id' => 14,
                'name'=> 'Survey Link',
                'description' => 'Demo',
                'key' => 'link'
            ],
            [
                'id'=> 37,
                'event_id' => 14,
                'name'=> 'Clinic Name',
                'description' => 'Demo',
                'key' => 'clinic_name'
            ],
            [
                'id'=> 38,
                'event_id' => 15,
                'name'=> 'Practice Name',
                'description' => 'Demo',
                'key' => 'practice_name'
            ],
            [
                'id'=> 39,
                'event_id' => 15,
                'name'=> 'Patient Name',
                'description' => 'Demo',
                'key' => 'patient_name'
            ],
            [
                'id'=> 40,
                'event_id' => 15,
                'name'=> 'Points',
                'description' => 'Demo',
                'key' => 'points'
            ],
            [
                'id'=> 41,
                'event_id' => 15,
                'name'=> 'Amount',
                'description' => 'Demo',
                'key' => 'amount'
            ],
            [
                'id'=> 42,
                'event_id' => 15,
                'name'=> 'Link',
                'description' => 'Demo',
                'key' => 'link'
            ],

        ];

        $table = $this->table('event_variables');
        $table->insert($data)->save();
    }
}