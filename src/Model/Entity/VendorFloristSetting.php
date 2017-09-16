<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorFloristSetting Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $product_id
 * @property string $product_image_url
 * @property string $message
 * @property string $address
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorFloristSetting extends Entity
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
