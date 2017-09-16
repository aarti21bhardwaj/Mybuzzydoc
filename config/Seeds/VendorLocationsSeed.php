<?php
use Migrations\AbstractSeed;

/**
 * VendorLocations seed.
 */
class VendorLocationsSeed extends AbstractSeed
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
                'id' => 1,
                'vendor_id' => 2,
                'address' => 'Address',
                'fb_url' => 'http://facebook.com',
                'google_url' => 'http://google.com',
                'yelp_url' => 'http://yelp.com',
                'ratemd_url' => 'http://ratemd.com',
                'yahoo_url' => 'http://yahoo.com',
                'healthgrades_url' => 'http://healthgrades.com',
                'hash_tag' => '#charizad',
                'is_default' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s'),
            ]
        ];

        $table = $this->table('vendor_locations');
        $table->insert($data)->save();
    }
}
