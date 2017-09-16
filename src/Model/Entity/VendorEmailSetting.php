<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorEmailSetting Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $email_layout_id
 * @property int $email_template_id
 * @property int $event_id
 * @property string $subject
 * @property string $body
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 * @property \App\Model\Entity\EmailLayout $email_layout
 * @property \App\Model\Entity\EmailTemplate $email_template
 */
class VendorEmailSetting extends Entity
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
