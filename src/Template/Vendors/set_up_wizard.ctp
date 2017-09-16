<div class="row" ng-app="setupwizard" ng-controller="SetupwizardController as SetUp">
    <div class="col-lg-12" >

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Welcome to BuzzyDoc! Please follow our setup wizard and allow us to setup your dashboard for you.') ?></h5>
            </div>
            <div class="ibox-content">
                <ul class = "list-inline">
                    <li>
                        <?= $this->Html->image('logo.png') ?>
                    </li>
                </ul>
                <hr>
                <div class="row" ng-if="!view">
                    <?= $this->Form->create($vendor, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                    <div class="ibox float-e-margins">
                        <div class="ibox-content" style="background-color:#cffcdd;">
                            <div class="row" ng-show="question.set == set" ng-repeat="question in questionnaire">
                                <div class="col-sm-12">
                                    <div class="" ng-class="{true:'text-center', false:'col-sm-offset-1 col-sm-11' }[question.set == 1]" ng-if="question.response_options[planName].length != 0">
                                        <h2>
                                            <strong>
                                                {{question.question_text}}
                                            </strong>
                                        </h2>
                                        <br>
                                    </div>

                                    <div ng-if="question.set == 1" ng-repeat="res in question.response_options" class="radio col-sm-offset-5" >
                                        <h3>
                                        <input type="radio" ng-model="radio[question.id]" name="question.question_type{{question.id}}" id="radio{{$index}}" ng-value="{{res.id}}">
                                        <label for="radio{{$index}}">
                                            <strong>
                                                {{res.option_text}}
                                            </strong>
                                        </label>
                                        </h3>
                                    </div>

                                    <div class = "col-sm-offset-2">
                                        <ul ng-if="question.question_type == 'checkbox' && question.response_options[planName].length != 0">
                                            <h3>
                                                <li class="list-inline" ng-repeat="res in question.response_options[planName]">
                                                    <!-- <input type="checkbox" ng-model="checkbox[res.id]" ng-if="question.id != 4" id="radio{{question.id}}{{$index}}" ng-true-value="{{res.linked_vendor_setting_id}}" ng-false-value="{{undefined}}" > -->
                                                    <input type="radio" ng-model="checkbox[question.id]" ng-if="question.id == 2" name="question.question_type{{question.id}}" id="radio{{question.id}}{{$index}}" ng-value="{{res.linked_vendor_setting_id}}">
                                                    <label for="radio{{question.id}}{{$index}}">
                                                        <strong>
                                                            {{res.option_text}}
                                                        </strong>
                                                    </label> 
                                                    <br><br>
                                                </li>
                                            </h3>
                                        </ul>
                                    </div>

                                    <div ng-if="question.question_type == 'radio' && question.set == 3" ng-repeat="res in question.response_options" class="radio col-sm-10 col-sm-offset-2" >
                                        <h3>
                                        <input type="radio" ng-model="radio[question.id]" name="question.question_type{{question.id}}" id="radio{{question.id}}{{$index}}" ng-value="{{res.industries_linked_templates}}">
                                            <label for="radio{{question.id}}{{$index}}">
                                                <strong>
                                                    {{res.option_text}}
                                                </strong>
                                            </label> 
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class ="row text-center">
                        <div class="btn-group" >
                            <button class="btn btn-success" type="button" ng-click="SetUp.questionNavigation(-1)" ng-disabled="set == 1">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-success" type="button" ng-click="SetUp.questionNavigation(1)" ng-disabled="set == 3">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class ="row text-center">
                        <div class="btn-group">
                            <button class="btn btn-primary" type="button" ng-show="set == 3" ng-click="SetUp.responseSubmission()">Save preferences</button>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>

                <!-- Second view starts here. -->
                <div class="row" ng-if="view">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="text-center" >
                                <h2>
                                    <strong>
                                        Template Selection
                                    </strong>
                                </h2>
                            </div>
                                <hr>
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h2>Recommended Template</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-10" style="margin-left:13px">
                                                <h3>
                                                    <strong class="col-sm-offset-1" ng-click="!fullStar ? fullStar = true : fullStar = false" style="cursor:pointer; text-transform:capitalize">
                                                       <i class="" ng-class="{true:'fa fa-star', false:'fa fa-star-o' }[fullStar == true]"></i> {{allTemplates[sortedTemplates[0][0]]}}
                                                    </strong>
                                                </h3>
                                            </div>
                                            <div class="col-sm-1 btn-group">
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#details" ng-click="SetUp.getTemplate(sortedTemplates[0][0])">Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h2>Other Templates</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-sm-offset-1" ng-repeat="template in sortedTemplates" ng-if="$index != 0" >
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <h3>
                                                    <input ng-disabled='fullStar == true' type="radio" ng-model="templateRadio.id" name="templates" ng-value="{{template[0]}}">
                                                        <strong style="text-transform:capitalize">
                                                            {{allTemplates[template[0]]}}
                                                        </strong>
                                                    </h3>
                                                </div>
                                                <div class="col-sm-2 btn-group pull-right">
                                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#details" ng-click="SetUp.getTemplate(template[0])">Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class ="row text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary" type="button" ng-click="SetUp.templateSubmission(fullStar)" ng-disabled = "(!fullStar && !templateRadio.id)">Apply Template</button>
                                </div>
                            </div>
                            <div class="modal inmodal fade" id="details"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title">Template Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="name text-center">
                                                <h3 style="text-transform:capitalize">{{viewTemplate.name}}</h3>
                                            </div>
                                            <div class="dd">
                                                <ul>
                                                    <li ng-if="viewTemplate.review"><h4>Review</h4>
                                                        <ul>
                                                            <li><dt>Initial Rating:</dt><dd>{{viewTemplate.review.review_points}}</dd></li>
                                                            <li><dt>Initial Review:</dt><dd>{{viewTemplate.review.rating_points}}</dd></li>
                                                            <li><dt>Facebook Review:</dt><dd>{{viewTemplate.review.fb_points}}</dd></li>
                                                            <li><dt>Google+ Review:</dt><dd>{{viewTemplate.review.gplus_points}}</dd></li>
                                                            <li><dt>Yelp! Review:</dt><dd>{{viewTemplate.review.yelp_points}}</dd></li>
                                                            <li><dt>Healthgrades Review:</dt><dd>{{viewTemplate.review.ratemd_points}}</dd></li>
                                                            <li><dt>Rate MDs:</dt><dd>{{viewTemplate.review.healthgrades_points}}</dd></li>
                                                            <li><dt>Yahoo:</dt><dd>{{viewTemplate.review.yahoo_points}}</dd></li>
                                                        </ul>
                                                    </li>
                                                    <li ng-if="viewTemplate.referral"><h4>Referral</h4></li>
                                                    <li ng-if="viewTemplate.template_promotions.length">
                                                        <h4>Promotions</h4>
                                                        <ul>
                                                            <li ng-repeat="promotion in viewTemplate.template_promotions">
                                                                <dt>{{promotion.promotion.name}} :</dt><dd>{{promotion.promotion.points}}</dd>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li ng-if="viewTemplate.template_surveys.length">
                                                        <h4>Survey(s)</h4>
                                                        <ul>
                                                            <li ng-repeat="survey in viewTemplate.template_surveys">
                                                                <dt>{{survey.survey.name}} :</dt>
                                                                <ul>
                                                                    <li ng-repeat="question in survey.survey.survey_questions">
                                                                        <dt>{{question.question.text}} :</dt><dd>{{question.question.points}}</dd>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li ng-if="viewTemplate.tier">
                                                        <h4>Tier(s)</h4>
                                                        <ul>
                                                            <li ng-repeat="tier in viewTemplate.tier">
                                                                <dt>{{tier.name}} :</dt>
                                                                <ul>
                                                                    <li>
                                                                        <dt>Spend upto :</dt><dd>{{tier.upperbound}}</dd>
                                                                    </li>
                                                                    <li>
                                                                        <dt>Earn(%) :</dt><dd>{{(tier.multiplier)*100}}</dd>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>