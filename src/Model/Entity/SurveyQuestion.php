<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveyQuestion Entity
 *
 * @property int $id
 * @property int $survey_id
 * @property int $question_id
 *
 * @property \App\Model\Entity\Survey $survey
 * @property \App\Model\Entity\Question $question
 * @property \App\Model\Entity\VendorSurveyQuestion[] $vendor_survey_questions
 */
class SurveyQuestion extends Entity
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
