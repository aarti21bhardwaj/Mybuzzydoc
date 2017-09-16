<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;
use Cake\Datasource\ConnectionManager;

/**
 * Staff Report Shell Command
 */
class EventVariablesShell extends Shell
{

	public function insertUsernameForWelcomePatient(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');
    $eventVariable = [

                        'id'=> 16,
                        'event_id' => 9,
                        'name'=> 'Username',
                        'description' => 'Demo',
                        'key' => 'username'
                     ];

    $eventVariable =  $this->EventVariables->newEntity($eventVariable);

    if(!$this->EventVariables->save($eventVariable)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

  public function insertFirstLastNameForReferrals(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');
    $eventVariables = [  
                        [
    
                          'id'=> 17,
                          'event_id' => 4,
                          'name'=> 'First Name',
                          'description' => 'Demo',
                          'key' => 'first_name'
                        ],
                        [

                          'id'=> 18,
                          'event_id' => 4,
                          'name'=> 'Last Name',
                          'description' => 'Demo',
                          'key' => 'last_name'
                        ]
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

  public function insertVarsForPatientResetPassword(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');

    $event = $this->EventVariables->Events->findByName('Patient Reset Password')->first();
    if(!$event){

      echo "Event Not Found"; die;
    }
    
    $eventVariables = [  
                        [
                            'id'=> 19,
                            'event_id' => $event->id,
                            'name'=> 'Patient Name',
                            'description' => 'Demo',
                            'key' => 'patient_name'
                        ],
                        [
                            'id'=> 20,
                            'event_id' => $event->id,
                            'name'=> 'Email',
                            'description' => 'Demo',
                            'key' => 'email'
                        ],
                        [
                            'id'=> 21,
                            'event_id' => $event->id,
                            'name'=> 'Vendor Name',
                            'description' => 'Demo',
                            'key' => 'org_name'
                        ],
                        [
                            'id'=> 22,
                            'event_id' => $event->id,
                            'name'=> 'Reset Link',
                            'description' => 'Demo',
                            'key' => 'reset_link'
                        ],
                        [
                            'id'=> 20,
                            'event_id' => $event->id,
                            'name'=> 'Patient Portal Link',
                            'description' => 'Demo',
                            'key' => 'ref'
                        ],
                        [
                            'id'=> 20,
                            'event_id' => $event->id,
                            'name'=> 'Patient Username',
                            'description' => 'Demo',
                            'key' => 'username'
                        ],
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }


  public function insertVarsForGiftCoupons(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');

    $event = $this->EventVariables->Events->findByName('Gift Coupons')->first();
    if(!$event){

      echo "Event Not Found"; die;
    }
    
    $eventVariables = [  
                        [
                            'id'=> 25,
                            'event_id' => $event->id,
                            'name'=> 'Patient Name',
                            'description' => 'Demo',
                            'key' => 'patient_name'
                        ],
                        [
                            'id'=> 26,
                            'event_id' => $event->id,
                            'name'=> 'Link',
                            'description' => 'Demo',
                            'key' => 'link'
                        ],
                        [
                            'id'=> 27,
                            'event_id' => $event->id,
                            'name'=> 'Organization Name',
                            'description' => 'Demo',
                            'key' => 'org_name'
                        ],
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

  public function insertVarsForSelfSignUpNotification(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');

    $event = $this->EventVariables->Events->findByName('Self Sign Up Notification')->first();
    if(!$event){

      echo "Event Not Found"; die;
    }
    
    $eventVariables = [  
                        [
                            'id'=> 28,
                            'event_id' => $event->id, 
                            'name'=> 'Practice Name',
                            'description' => 'Demo',
                            'key' => 'practice_name'
                        ],
                        [
                            'id'=> 29,
                            'event_id' => $event->id,
                            'name'=> 'Patient Name',
                            'description' => 'Demo',
                            'key' => 'patient_name'
                        ],
                        [
                            'id'=> 30,
                            'event_id' => $event->id,
                            'name'=> 'Patient Email',
                            'description' => 'Demo',
                            'key' => 'email'
                        ],
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

  public function insertVarsForRedemptionNotification(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');

    $event = $this->EventVariables->Events->findByName('Redemption Notification')->first();
    if(!$event){

      echo "Event Not Found"; die;
    }
    
    $eventVariables = [  
                        [
                            'id'=> 31,
                            'event_id' => $event->id,
                            'name'=> 'Practice Name',
                            'description' => 'Demo',
                            'key' => 'practice_name'
                        ],
                        [
                            'id'=> 32,
                            'event_id' => $event->id,
                            'name'=> 'Patient Name',
                            'description' => 'Demo',
                            'key' => 'patient_name'
                        ],
                        [
                            'id'=> 33,
                            'event_id' => $event->id,
                            'name'=> 'Redemption Type',
                            'description' => 'Demo',
                            'key' => 'redemption_type'
                        ],
                        [
                            'id'=> 34,
                            'event_id' => $event->id,
                            'name'=> 'Points',
                            'description' => 'Demo',
                            'key' => 'points'
                        ],
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariable);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

  public function insertVarsForAssessmentSurveys(){

    $conn = ConnectionManager::get('default');
    $conn->driver()->autoQuoting(true);

    $this->loadModel('EventVariables');
    $eventVariables = [  
                        [
                            'id'=> 35,
                            'event_id' => 14,
                            'name'=> 'Patient Name',
                            'description' => 'Demo',
                            'key' => 'patient_name'
                        ],
                        [
                            'id'=> 36,
                            'event_id' => 14,
                            'name'=> 'Survey Link',
                            'description' => 'Demo',
                            'key' => 'link'
                        ],
                        [
                            'id'=> 37,
                            'event_id' => 14,
                            'name'=> 'Clinic Name',
                            'description' => 'Demo',
                            'key' => 'clinic_name'
                        ]
                      ];
    $eventVariables =  $this->EventVariables->newEntities($eventVariables);
    // pr($eventVariable);die;

    if(!$this->EventVariables->saveMany($eventVariables)){
      
      echo "Insertion Failed";
      pr($eventVariables);die;
    
    }else{

      echo "Insertion Successful";
    }

  }

}