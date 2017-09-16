<div id="loading-bar" style="display: none;"><div id="loading-bar-container"><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div><div class="sk-rect2"></div><div class="sk-rect3"></div><div class="sk-rect4"></div><div class="sk-rect5"></div></div></div></div>
<?php if(!$assessmentSurveyInstanceId):?>
	<h2>Survey has been submitted. Thank you!</h2>
<?php endif; ?>
<?php if($assessmentSurveyInstanceId):?>
<div class="row" ng-app="PatientReportedOutcomes" ng-cloak>
    <div class="col-lg-12" ng-controller="MainController">
        <div class="ibox float-e-margins" ng-show="showContent">
            <div class="ibox-title">
                <h5><?= __('Please answer every question based on your condition by selecting the appropriate emoticon.') ?></h5>
            </div>
            
            <div class="ibox-content">
                <div class='row'>
                	<!-- <h4 class="">Fill this survey for let us know how you are doing.</h4> -->
                	<br><br>
                	<div class=" col-sm-offset-1 col-sm-10">
		                <swiper on-init="onInit">
		                	<slides>
		                	<slide ng-repeat="question in assessmentSurvey.vendor_assessment_survey_questions">
                                <div class="row" >

                                    <div class="col-sm-offset-1 col-sm-10" ng-init="initResponse($index, question.id)">
                                    	<p>
	                                        Q{{$index+1}}) {{question.assessment_survey_question.text}}
                                    	</p>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <br>
                                	<div class = 'col-sm-12'>
                                		<div class="row">
	                                		<br ng-show="responseOptions[question.assessment_survey_question.response_group_id].length > 2">
	                                        <span ng-repeat="option in responseOptions[question.assessment_survey_question.response_group_id]" ng-class="{'col-sm-4':responseOptions[question.assessment_survey_question.response_group_id].length > 2, 'row' : responseOptions[question.assessment_survey_question.response_group_id].length <= 2}">
	                                            <label>
	                                                <input ng-style="getRadioStyle(option.response_group_id)" ng-click="nextSlide()" type="radio" ng-value="option.id" ng-model="request.assessment_survey_instance_responses[$parent.$index].response_option_id">
	                                                <i class="fa fa-3x {{smileyClass[option.weightage]}}" ng-show="option.response_group_id == 2" id="R{{question.id}}{{option.id}}" ng-style="getSmileyStyle(option.id, $parent.$index)"></i>
	                                                <strong ng-if="option.response_group_id == 1">{{option.label}}</strong>
	                                            </label>
	                                        </span>
	                                    </div>
	                                    <br><br>
                                	</div>
                                 </div>
		                	</slide>
		                	<slide>
		                		<div class="row">
		                			<br><br>
	                                <button class="btn btn-primary btn-lg" ng-click="saveResponse()">
	                                	Submit
	                                </button>
		                		</div>
		                	</slide>
		                	</slides>
		                	<prev></prev>
		                	<next></next>
		                	<pagination></pagination>
		                </swiper>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var surveyId = '<?= $assessmentSurveyInstanceId?>';
</script>
<?= $this->Html->script(['patient-reported-outcomes']) ?>
<?php endif; ?>
