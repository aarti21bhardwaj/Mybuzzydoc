<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorLocation Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $address
 * @property string $fb_url
 * @property string $google_url
 * @property string $yelp_url
 * @property string $ratemd_url
 * @property string $healthgrades_url
 * @property bool $is_default
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $hash_tag
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\VendorReview[] $vendor_reviews
 */
class VendorLocation extends Entity
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
