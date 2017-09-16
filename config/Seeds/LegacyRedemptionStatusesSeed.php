<?php
use Migrations\AbstractSeed;

/**
 * LegacyRedemptionStatuses seed.
 */
class LegacyRedemptionStatusesSeed extends AbstractSeed
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
                        'name' => 'In office',
                    ],
                    [
                        'name' => 'Ordered/Shipped',
                    ],
                    [
                        'name' => 'Redeemed',
                    ],
                ];

        $table = $this->table('legacy_redemption_statuses');
        $table->insert($data)->save();
    }
}
