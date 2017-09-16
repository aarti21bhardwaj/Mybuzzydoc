<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EventVariable Entity
 *
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string $description
 * @property string $key
 *
 * @property \App\Model\Entity\Event $event
 */
class EventVariable extends Entity
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
