<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TemplatePromotion Entity
 *
 * @property int $id
 * @property int $template_id
 * @property int $promotion_id
 *
 * @property \App\Model\Entity\Template[] $templates
 * @property \App\Model\Entity\Promotion $promotion
 */
class TemplatePromotion extends Entity
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
