<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Referral Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $refer_from
 * @property string $refer_to
 * @property bool $status
 * @property \Cake\I18n\Time $is_deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $name
 * @property string $phone
 * @property string $peoplehub_identifier
 * @property string $uuid
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class Referral extends Entity
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
