<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorCardRequest Entity
 *
 * @property int $id
 * @property string $vendor_card_series
 * @property int $vendor_id
 * @property bool $status
 * @property bool $is_issued
 * @property string $remark
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $is_deleted
 * @property int $count
 * @property int $start
 * @property int $end
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorCardRequest extends Entity
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
