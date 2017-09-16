<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;

/**
 * ReferralLeads Controller
 *
 * @property \App\Model\Table\ReferralLeadsTable $ReferralLeads
 */
class SetUpWizardController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|void Redirects on successful show the response from AJAX, renders view for the template data.
     */
    public function index($id=null){

        if($this->request->is('get')){

            $vendorPlan = $this->Auth->user('plan')->plan->name;

            $data = [
                  [
                    'id' => '1',
                    'question_text'=>'Which Industry do you belong to?',
                    'question_type'=>'radio',
                    'set' => '1',
                    'response_options'=> [
                                            [
                                              'id'=>1,
                                              'option_text'=>'Orthodontics',
                                              'linked_templates' => [1,7,17]
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'Optometry',
                                              'linked_templates' => [2,10,14,20,24]
                                            ],
                                            [
                                              'id'=>3,
                                              'option_text'=>'General Dentistry',
                                              'linked_templates' => [3,8,16,18,26]
                                            ],
                                            [
                                              'id'=>4,
                                              'option_text'=>'Audiology',
                                              'linked_templates' => [4,12,22]
                                            ],
                                            [
                                              'id'=>5,
                                              'option_text'=>'Medical Aesthetics',
                                              'linked_templates' => [5,9,13,19,23]
                                            ],
                                            [
                                              'id'=>6,
                                              'option_text'=>'Veterinary',
                                              'linked_templates' => [6,11,15,21,25]
                                            ]
                                          ]
                  ],
                  [
                    'id' => '2',
                    'question_text'=>'What kind of credit would you like to give to your patients?',
                    'question_type'=>'checkbox',
                    'set' => '2',
                    'response_options'=>[

                        'Good' => [
                                        [
                                          'id'=>1,
                                          'option_text'=>'Store Credit, which can only be redeemed against gifts/coupons or services of your practice.',
                                          'linked_vendor_setting_id' => [4],
                                        ],
                                        [
                                          'id'=>2,
                                          'option_text'=>'Wallet credit, can be used for availing products through amazon/tango gift cards.',
                                          'linked_vendor_setting_id' => [44],
                                        ],
                                    ],
                        'Better' => [
                                        [
                                          'id'=>1,
                                          'option_text'=>'Store Credit, which can only be redeemed against gifts/coupons or services of your practice.',
                                          'linked_vendor_setting_id' => [4],
                                        ],
                                        [
                                          'id'=>2,
                                          'option_text'=>'Wallet credit, can be used for availing products through amazon/tango gift cards.',
                                          'linked_vendor_setting_id' => [44],
                                        ],
                                    ],
                        'Best'  => [
                                        [
                                          'id'=>1,
                                          'option_text'=>'Store Credit, which can only be redeemed against gifts/coupons or services of your practice.',
                                          'linked_vendor_setting_id' => [4],
                                        ],
                                        [
                                          'id'=>2,
                                          'option_text'=>'Wallet credit, can be used for availing products through amazon/tango gift cards.',
                                          'linked_vendor_setting_id' => [44],
                                        ],
                                    ]

                           ]
                  ],
                  [
                    'id' => '3',
                    'question_text'=>"What are your initial thoughts on budget for how much you'll want to spend on each patient, annually, on treatment compliance?",
                    'question_type'=>'radio',
                    'set' => '3',
                    'response_options'=>[
                                            [
                                              'id'=>1,
                                              'option_text'=>'$5-10',
                                              'industries_linked_templates' => [
                                                                                    1 => [1],
                                                                                    2 => [2],
                                                                                    3 => [3],
                                                                                    4 => [4],
                                                                                    5 => [5],
                                                                                    6 => [6],
                                                                                ],
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'$10-15',
                                              'industries_linked_templates' => [
                                                                                    1 => [7],
                                                                                    2 => [10],
                                                                                    3 => [8],
                                                                                    4 => [12],
                                                                                    5 => [9],
                                                                                    6 => [11],
                                                                                ],
                                            ],
                                            [
                                              'id'=>3,
                                              'option_text'=>'$15-20',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [14],
                                                                                    3 => [16],
                                                                                    4 => [],
                                                                                    5 => [13],
                                                                                    6 => [15],
                                                                                    ],
                                            ],
                                            [
                                              'id'=>4,
                                              'option_text'=>'$20-25',
                                              'industries_linked_templates' => [
                                                                                    1 => [17],
                                                                                    2 => [20],
                                                                                    3 => [18],
                                                                                    4 => [22],
                                                                                    5 => [19],
                                                                                    6 => [21],
                                                                                ],
                                            ],
                                            [
                                              'id'=>5,
                                              'option_text'=>'$25+',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [24],
                                                                                    3 => [26],
                                                                                    4 => [],
                                                                                    5 => [23],
                                                                                    6 => [25],
                                                                                ],
                                            ],
                                        ],
                  ],
                  [
                    'id' => '4',
                    'question_text'=>"What are your initial thoughts on budget for how much you'll want to spend on each patient, annually, on social media?",
                    'question_type'=>'radio',
                    'set' => '3',
                    'response_options'=>[
                                            [
                                              'id'=>1,
                                              'option_text'=>'$5-10',
                                              'industries_linked_templates' => [
                                                                                    1 => [1],
                                                                                    2 => [2],
                                                                                    3 => [3],
                                                                                    4 => [4],
                                                                                    5 => [5],
                                                                                    6 => [6],
                                                                                ],
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'$10-15',
                                              'industries_linked_templates' => [
                                                                                    1 => [7],
                                                                                    2 => [10],
                                                                                    3 => [8],
                                                                                    4 => [12],
                                                                                    5 => [9],
                                                                                    6 => [11],
                                                                                ],
                                            ],
                                            [
                                              'id'=>3,
                                              'option_text'=>'$15-20',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [14],
                                                                                    3 => [16],
                                                                                    4 => [],
                                                                                    5 => [13],
                                                                                    6 => [15],
                                                                                    ],
                                            ],
                                            [
                                              'id'=>4,
                                              'option_text'=>'$20-25',
                                              'industries_linked_templates' => [
                                                                                    1 => [17],
                                                                                    2 => [20],
                                                                                    3 => [18],
                                                                                    4 => [22],
                                                                                    5 => [19],
                                                                                    6 => [21],
                                                                                ],
                                            ],
                                            [
                                              'id'=>5,
                                              'option_text'=>'$25+',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [24],
                                                                                    3 => [26],
                                                                                    4 => [],
                                                                                    5 => [23],
                                                                                    6 => [25],
                                                                                ],
                                            ],
                                        ],
                  ],
                  [
                    'id' => '5',
                    'question_text'=>"Do you want special benefits/greetings on occasions such as birthdays, anniversaries, festivals or holidays?",
                    'question_type'=>'radio',
                    'set' => '3',
                    'response_options'=>[
                                            [
                                              'id'=>1,
                                              'option_text'=>'Yes',
                                              'industries_linked_templates' => [
                                                                                    1 => [1,7,17],
                                                                                    2 => [2,10,14,20,24],
                                                                                    3 => [3,8,16,18,26],
                                                                                    4 => [4,12,22],
                                                                                    5 => [5,9,13,19,23],
                                                                                    6 => [6,11,15,21,25],
                                                                                ],
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'No',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [],
                                                                                    3 => [],
                                                                                    4 => [],
                                                                                    5 => [],
                                                                                    6 => [],
                                                                                ],
                                            ],
                                        ],
                  ],
                  [
                    'id' => '6',
                    'question_text'=>'Would you like to allow your patients to use their points on third party e-gift codes like Amazon.com, internal gift certificates or are you interested in using both?',
                    'question_type'=>'radio',
                    'set' => '3',
                    'response_options'=>[
                                            [
                                              'id'=>1,
                                              'option_text'=>'Please enable internal gift certificates',
                                              'industries_linked_templates' => [
                                                                                    1 => [1],
                                                                                    2 => [2],
                                                                                    3 => [3],
                                                                                    4 => [4],
                                                                                    5 => [5],
                                                                                    6 => [6],
                                                                                ],
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'Please enable third party e-gift codes like Amazon.com',
                                              'industries_linked_templates' => [
                                                                                    1 => [7,17],
                                                                                    2 => [10,14,20,24],
                                                                                    3 => [8,16,18,26],
                                                                                    4 => [12,22],
                                                                                    5 => [9,13,19,23],
                                                                                    6 => [11,15,21,25],
                                                                                ],
                                            ],
                                            [
                                              'id'=>3,
                                              'option_text'=>'Enable both internal gift certificates & third party e-gift codes like Amazon.com',
                                              'industries_linked_templates' => [
                                                                                    1 => [1,7,17],
                                                                                    2 => [2,10,14,20,24],
                                                                                    3 => [3,8,16,18,26],
                                                                                    4 => [4,12,22],
                                                                                    5 => [5,9,13,19,23],
                                                                                    6 => [6,11,15,21,25],
                                                                                ],
                                            ],
                                        ],
                  ],
                  [
                    'id' => '7',
                    'question_text'=>'In what ways would you like to motivate your patients?',
                    'question_type'=>'radio',
                    'set' => '3',
                    'response_options'=>[
                                            [
                                              'id'=>1,
                                              'option_text'=>'We would like to get feedback on visits and overall practice reviews',
                                              'industries_linked_templates' => [
                                                                                    1 => [1,7,17],
                                                                                    2 => [2,10,14,20,24],
                                                                                    3 => [3,8,16,18,26],
                                                                                    4 => [4,12,22],
                                                                                    5 => [5,9,13,19,23],
                                                                                    6 => [6,11,15,21,25],
                                                                                ],
                                            ],
                                            [
                                              'id'=>2,
                                              'option_text'=>'We would like to improve patient retention.',
                                              'industries_linked_templates' => [
                                                                                    1 => [1,7,17],
                                                                                    2 => [2,10,14,20,24],
                                                                                    3 => [3,8,16,18,26],
                                                                                    4 => [4,12,22],
                                                                                    5 => [5,9,13,19,23],
                                                                                    6 => [6,11,15,21,25],
                                                                                ],
                                            ],
                                            [
                                              'id'=>3,
                                              'option_text'=>'We would like to increase the number of products and/or services our patients consume on each visit.',
                                              'industries_linked_templates' => [
                                                                                    1 => [],
                                                                                    2 => [14,24],
                                                                                    3 => [16,26],
                                                                                    4 => [],
                                                                                    5 => [13,23],
                                                                                    6 => [15,25],
                                                                                ],
                                            ],
                                            [
                                              'id'=>4,
                                              'option_text'=>'We would like to improve our patient engagement in their treatment.',
                                              'industries_linked_templates' => [
                                                                                    1 => [7,17],
                                                                                    2 => [10,14,20,24],
                                                                                    3 => [8,16,18,26],
                                                                                    4 => [12,22],
                                                                                    5 => [9,13,19,23],
                                                                                    6 => [11,15,21,25],
                                                                                ],
                                            ],
                                        ],
                  ]
                ];

            $this->set('questionnaire',$data);
            $this->set('plan', $vendorPlan);
            $this->set('_serialize', ['questionnaire','plan']);

        }
    }

    public function saveSetupWizard(){

      if($this->request->is('post')){

        $this->_saveTemplate($this->request->data['template_id']);
        $this->_saveVendorSettings($this->request->data['vendor_settings']);

      }

      $this->set('response', ['status' => 'okay']);
      $this->set('_serialize', 'response');
    }

    //In this method, the credit type id in setting_keys is 4 and for distinction purposes when our function sees id 4 it'll take it as store_credit and for 44 it'll mark wallet_credit as the option selected for setting_keys id 4.
    private function _saveVendorSettings($settingsArray){

      $this->loadModel('VendorSettings');
      
      $vendorPlan = $this->Auth->user('plan')->plan->name;
      switch ($vendorPlan) {
          case 'Good':
              $preSettings = [];
              break;
          case 'Better':
              $preSettings = [3,4,7,8,9,10];
              break;
          case 'Best':
              $preSettings = [1,2,3,4,7,8,9,10];
              break;
        }

      $vSettings = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
                                        ->all()
                                        ->indexBy('setting_key_id')
                                        ->toArray();
      if(in_array(44, $settingsArray)){

        $key = array_search(4, $preSettings);
        unset($preSettings[$key]);
      }

      $settingsArray = array_unique(array_merge($preSettings, $settingsArray));

      $settings = [];
      foreach ($settingsArray as $settingId) {

        if(!isset($vSettings[$settingId]) && $settingId != 44){

            // $settingId = 4 for store_credit and $settingId = 44 for wallet_credit
          if($settingId == 4 || $settingId == 44){
              if($settingId == 4){

                $settings[] = [
                              'vendor_id' => $this->Auth->user('vendor_id'),
                              'setting_key_id' => 4,
                              'value' => 'store_credit',
                            ];
              }else{
                
                $settings[] = [
                              'vendor_id' => $this->Auth->user('vendor_id'),
                              'setting_key_id' => 4,
                              'value' => 'wallet_credit',
                            ];
              }

          }else{

            if($settingId == 7){
              $settings[] = [
                              'vendor_id' => $this->Auth->user('vendor_id'),
                              'setting_key_id' => $settingId,
                              'value' => 0,
                            ];
            }else{
              // All of the rest are boolean.
              $settings[] = [
                              'vendor_id' => $this->Auth->user('vendor_id'),
                              'setting_key_id' => $settingId,
                              'value' => 1,
                            ];
              }
          }

        }else{

          if($settingId == 4 || $settingId == 44){
              if($settingId == 4){

                $vSettings[4]->value = 'store_credit';
                $singleVSetting = $vSettings[4];
              }else{
                
                $vSettings[4]->value = 'wallet_credit';
                $singleVSetting = $vSettings[4];
              }
              
              if(!($singleVSetting = $this->VendorSettings->save($singleVSetting))){
                throw new InternalErrorException('NOT_SAVED: Vendor settings');
              }
          }else{

            if($settingId == 7){
              $vSettings[$settingId]->value = 0;
            }else{
              // All of the rest are boolean.
              $vSettings[$settingId]->value = 1;
            }
            $singleVSetting = $vSettings[$settingId];

            if(!($singleVSetting = $this->VendorSettings->save($singleVSetting))){
                throw new InternalErrorException('NOT_SAVED: Vendor settings');
              }
          }
        }
      }
        
      if($settings != null){
        $settingsSave = $this->VendorSettings->newEntities($settings);
        $settingsSave = $this->VendorSettings->saveMany($settingsSave);
        
        if(!$settingsSave){
          throw new InternalErrorException('NOT_SAVED: Vendor settings');
        }
      }

      return true;
    }

    private function _saveTemplate($template_id){

      $this->loadModel('Vendors');
      $vendor = $this->Vendors->findById($this->Auth->user('vendor_id'))->first();
      if(!$vendor){
        throw new NotFoundException('NOT_FOUND: Vendor');
      }else {

          $patchData = ['template_id' => $template_id];
          $vendor = $this->Vendors->patchEntity($vendor, $patchData);

          if (!($this->Vendors->save($vendor))) {
              throw new NotFoundException('ENTITY_ERROR: Vendor could not be saved.');
          } else {
              $this->Vendors->applyTemplate($vendor);
          }
      }
      return true;

    }

}
