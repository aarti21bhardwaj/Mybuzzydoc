<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * AuthorizeNetProfile Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $profile_identifier
 * @property bool $is_card_setup
 * @property int $payment_profileid
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 */
class AuthorizeNetProfile extends Entity
{

    use AuditLogTrait;

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
