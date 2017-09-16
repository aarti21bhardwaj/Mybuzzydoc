<?php
use Migrations\AbstractSeed;

/**
 * ReviewTypes seed.
 */
class ReviewTypesSeed extends AbstractSeed
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
                'name' => 'review',
            ],
            [
                'name' => 'rating',
            ],
            [
                'name'=> 'fb',
            ],
            [
                'name'=> 'gplus',
            ],
            [
                'name'=> 'yelp',
            ],
            [
                'name'=> 'ratemd',
            ],
            [
                'name'=> 'healthgrades',
            ],
            [
                'name' => 'yahoo'
            ]
        ];

        $table = $this->table('review_types');
        $table->insert($data)->save();
    }
}
