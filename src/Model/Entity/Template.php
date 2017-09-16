<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Template Entity
 *
 * @property int $id
 * @property string $name
 * @property string $review
 * @property string $referral
 * @property string $tier
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property string $milestone
 * @property string $gift_coupon
 *
 * @property \App\Model\Entity\IndustryTemplate[] $industry_templates
 * @property \App\Model\Entity\TemplatePlan[] $template_plans
 * @property \App\Model\Entity\TemplatePromotion[] $template_promotions
 * @property \App\Model\Entity\TemplateSurvey[] $template_surveys
 * @property \App\Model\Entity\VendorTemplateMilestone[] $vendor_template_milestones
 * @property \App\Model\Entity\Vendor[] $vendors
 */
class Template extends Entity
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
