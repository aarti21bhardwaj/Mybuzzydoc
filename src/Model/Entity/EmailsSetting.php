<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailsSetting Entity
 *
 * @property int $id
 * @property int $email_layout_id
 * @property string $email_template_id
 * @property string $email_event
 * @property string $subject
 * @property string $body
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Layout $layout
 * @property \App\Model\Entity\Template $template
 */
class EmailsSetting extends Entity
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
