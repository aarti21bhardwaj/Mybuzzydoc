<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorFloristTransaction Entity
 *
 * @property int $id
 * @property int $vendor_florist_order_id
 * @property int $response_id
 *
 * @property \App\Model\Entity\VendorFloristOrder $vendor_florist_order
 * @property \App\Model\Entity\Response $response
 */
class VendorFloristTransaction extends Entity
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
