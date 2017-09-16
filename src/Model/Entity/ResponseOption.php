<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResponseOption Entity
 *
 * @property int $id
 * @property int $response_group_id
 * @property string $name
 * @property string $label
 * @property int $weightage
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\ResponseGroup $response_group
 * @property \App\Model\Entity\AssessmentSurveyInstanceResponse[] $assessment_survey_instance_responses
 */
class ResponseOption extends Entity
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
