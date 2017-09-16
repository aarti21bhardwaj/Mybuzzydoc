<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorReview Entity
 *
 * @property int $id
 * @property int $vendor_location_id
 * @property string $review
 * @property float $rating
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $uuid
 *
 * @property \App\Model\Entity\VendorLocation $vendor_location
 * @property \App\Model\Entity\ReviewRequestStatus[] $review_request_statuses
 */
class VendorReview extends Entity
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
