<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorFloristOrder Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $user_id
 * @property string $product_code
 * @property string $image_url
 * @property string $price
 * @property bool $status
 * @property string $message
 * @property \Cake\I18n\Time $delivery_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $patient_peoplehub_identifier
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\VendorFloristTransaction[] $vendor_florist_transactions
 */
class VendorFloristOrder extends Entity
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
