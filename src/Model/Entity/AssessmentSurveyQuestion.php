<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssessmentSurveyQuestion Entity
 *
 * @property int $id
 * @property int $assessment_survey_id
 * @property int $response_group_id
 * @property string $text
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\AssessmentSurvey $assessment_survey
 * @property \App\Model\Entity\ResponseGroup $response_group
 * @property \App\Model\Entity\VendorAssessmentSurveyQuestion[] $vendor_assessment_survey_questions
 */
class AssessmentSurveyQuestion extends Entity
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
