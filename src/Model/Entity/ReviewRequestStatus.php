<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReviewRequestStatus Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_review_id
 * @property bool $rating
 * @property bool $review
 * @property bool $fb
 * @property bool $google_plus
 * @property bool $yelp
 * @property bool $ratemd
 * @property bool $healthgrades
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $vendor_location_id
 * @property int $people_hub_identifier
 * @property string $email_address
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\VendorReview $vendor_review
 * @property \App\Model\Entity\VendorLocation $vendor_location
 */
class ReviewRequestStatus extends Entity
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
