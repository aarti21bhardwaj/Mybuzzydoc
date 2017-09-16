<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;

/**
 * Staff Report Shell Command
 */
class EventsShell extends Shell
{

	public function insertPatientResetPassword(){

   $this->insertEvent('Patient Reset Password');
  }
  
  public function insertNotifications(){

    $this->insertEvent('Gift Coupons');
    $this->insertEvent('Self Sign Up Notification');
    $this->insertEvent('Redemption Notification');
  }

  public function insertEvent($name){

    $this->loadModel('Events');
    $event = [
                'name'=> $name,
                'created' => date('Y-m-d H:i:s'),
                'modified'=> date('Y-m-d H:i:s')
            ];

    $event =  $this->Events->newEntity($event);

    if(!$this->Events->save($event)){
      
      echo "Insertion Failed";
      pr($event);die;
    
    }else{

      echo "Insertion Successful";
    }

  }
}