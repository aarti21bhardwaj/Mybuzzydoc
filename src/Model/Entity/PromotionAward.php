<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PromotionAward Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $points
 * @property int $peoplehub_transaction_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $vendor_promotion_id
 * @property int $redeemer_peoplehub_identifier
 * @property int $vendor_id
 * @property int $multiplier
 * @property \Cake\I18n\Time $is_deleted
 * @property int $deletion_transaction_number
 * @property string $description
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Promotion $promotion
 * @property \App\Model\Entity\VendorPromotion $vendor_promotion
 */
class PromotionAward extends Entity
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
