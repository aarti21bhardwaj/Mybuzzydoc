<?php
use Migrations\AbstractSeed;

/**
 * Features seed.
 */
class FeaturesSeed extends AbstractSeed
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
                'name'=> 'email',
            ],
            [
                'name'=> 'instantcredit',
            ],
            [
                'name'=> 'instantredemption',
            ],
            [
                'name'=> 'promotions',
            ],
            [
                'name'=> 'referral',
            ],
            [
                'name'=> 'review',
            ],
            [
                'name'=> 'compliancesurvey', //survey UI for best patient
            ],
            [
                'name'=> 'patienthistory',
            ],
            [
                'name'=> 'staffhistory',
            ],
            [
                'name'=> 'manualpoints',
            ],
            [
                'name'=> 'giftcoupons',
            ],
            [
                'name'=> 'tier',
            ],
            [
                'name'=> 'inhouseredemption',
            ],
            [
                'name'=> 'redeemwallet',
            ],
            [
                'name'=> 'instantreward',
            ],

        ];

        $table = $this->table('features');
        $table->insert($data)->save();
    }
}
