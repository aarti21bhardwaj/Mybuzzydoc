<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveyInstanceResponse Entity
 *
 * @property int $id
 * @property int $vendor_survey_instance_id
 * @property int $vendor_survey_question_id
 * @property string $response
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VendorSurveyInstance $vendor_survey_instance
 * @property \App\Model\Entity\VendorSurveyQuestion $vendor_survey_question
 */
class SurveyInstanceResponse extends Entity
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
