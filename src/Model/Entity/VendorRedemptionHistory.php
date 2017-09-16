<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorRedemptionHistory Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property float $actual_balance
 * @property float $redeemed_amount
 * @property float $remaining_amount
 * @property float $cc_charged_amount
 * @property string $cc_transaction_identifier
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorRedemptionHistory extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
