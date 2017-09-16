<?php

namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Log\Log;

class GeneralMailer extends Mailer
{
    public function sendMail($arr)
    {
     	

       $hashData= $arr['hashData'];
       $eventId = $arr['eventId'];
       $email = false;


       //Fetch settings for email for default
        $this->loadModel('EmailSettings');
        Log::write('debug', json_encode($arr));
        $emailSettings = $this->EmailSettings->findByEventId($eventId)->first();
        if($emailSettings){
          $email = $emailSettings->toArray();
        }

       //Fetch settings for email for vendor
       $this->loadModel('VendorEmailSettings');
       if(isset($arr['vendor_id']) && $arr['vendor_id'] != null){
          $vendor_id = $arr['vendor_id'] ;
          $vendorEmailSettings = $this->VendorEmailSettings->findByEventId($eventId)
                                             ->where(['vendor_id'=>$vendor_id])->first();
           if($vendorEmailSettings)
           {
            //override the default email if we find an email for the vendor.
            $vendorEmail = $vendorEmailSettings->toArray();
           }
        }   

        //override admin email settings with vendor email settings if any
        if(isset($vendorEmail)){

          $propArray = ['from_email', 'recipients', 'subject', 'body'];
          foreach ($propArray as $key => $value) {
            if(isset($vendorEmail[$value]) && $vendorEmail[$value]){
              $email[$value] = $vendorEmail[$value];
            }
          }
        }

        if(!$email) {
          return;
        }
        
        //If no recipient is set then cancel sending email
        if((!isset($email['recipients']) || !$email['recipients']) && (!isset($hashData->email) || !$hashData->email) && (!isset($hashData->cc) || !$hashData->cc) && (!isset($hashData->bcc) || !$hashData->bcc)){
          $this->to('redemptions@buzzydoc.com');
        }

      // pr($vendorEmailSettings);die;
       // Log::write('debug', $email);   

       //Assign variables for substitution
       $content = $this->substitute($email['body'], $hashData);
       $subject = $this->substitute($email['subject'], $hashData);
       $this->subject($subject)
            ->template('default')
            ->layout('default')
            ->emailFormat('html')
            ->set('content', $content);

        //Check if from email is set in email settings
        if(isset($email['from_email']) && $email['from_email']){
          $this->from($email['from_email']);
        }

        //Check if recipient's email is set in hash data
        if(isset($hashData->email) && $hashData->email){
          $this->to($hashData->email);
        }

        //Check if any cc is set in hash data
        if(isset($hashData->cc) && $hashData->cc){
          $this->cc($hashData->cc);
        }

        //Check if any bcc is set in hash data
        if(isset($hashData->bcc) && $hashData->bcc){
          $this->bcc($hashData->bcc);
        }

        //Check if any recipients's are set in email settings.
        if(isset($email['recipients']) && $email['recipients']){
          $recipients = explode(',', $email['recipients']);
          foreach ($recipients as $key => $value) {
            $recipients[$key] = trim($value);
          }
          $this->addBcc($recipients);
        }        

    }
    public function substitute($content, $hash){

        //write substitute logic
        $hash = $hash->toArray();
        $i=0;
        foreach ($hash as $key => $value) {
            if(!is_array($value)){
                $placeholder = sprintf('{{%s}}', $key);
                if($placeholder=="{{".$key."}}"){
                    if(!$i){
                        $afterStr = str_replace($placeholder, $value, $content);
                    }else{
                        $afterStr = str_replace($placeholder, $value, $afterStr);
                    }
                    $i++;
                }
            }
        }
        return $afterStr;
    }
   
}
?>