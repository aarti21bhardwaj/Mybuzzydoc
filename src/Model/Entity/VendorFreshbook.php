<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorFreshbook Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $freshbook_client_id
 * @property int $recurring_profile_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\FreshbookClient $freshbook_client
 * @property \App\Model\Entity\RecurringProfile $recurring_profile
 */
class VendorFreshbook extends Entity
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
