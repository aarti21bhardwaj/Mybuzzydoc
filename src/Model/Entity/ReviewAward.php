<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReviewAward Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $points
 * @property int $peoplehub_transaction_id
 * @property int $review_type_id
 * @property int $review_request_status_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $redeemer_peoplehub_identifier
 * @property int $vendor_id
 *
 * @property \App\Model\Entity\VendorReview $vendor_review
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ReviewType $review_type
 */
class ReviewAward extends Entity
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
