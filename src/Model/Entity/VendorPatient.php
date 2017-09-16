<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorPatient Entity
 *
 * @property int $id
 * @property int $patient_peoplehub_id
 * @property int $vendor_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $old_buzzydoc_patient_identifier
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorPatient extends Entity
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
