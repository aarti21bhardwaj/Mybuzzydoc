<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;

/**
 * Staff Report Shell Command
 */
class VendorSettingsShell extends Shell
{

  // public function insertDocuments(){

  //   $this->insertSetting('Documents', 'boolean', 1);
  // }

  public function insertReferrals(){


    $this->insertSetting('Referrals', 'boolean', 1);
  }

  public function insertChatSupport(){


    $this->insertSetting('Chat', 'boolean', 0);
  
  }

  public function insertNewSettings(){

    $this->insertSetting('Instant Redeem', 'boolean', 0);
    $this->insertSetting('Express Gifts', 'boolean', 0);
  }

  public function insertSetting($name = null, $type = null, $defaultValue = null){
    if(!$name){
      echo "Setting Key name is required";die;
    }

    if(!$type){
      echo "Setting Key type is required";die;
    }

    $this->loadModel('SettingKeys');
    $setting = [
                  'name' => $name,
                  'type' => $type
               ];

    $setting =  $this->SettingKeys->newEntity($setting);

    if(!$this->SettingKeys->save($setting)){
      
      echo "Setting key Insertion Failed";
    
    }else{

      echo "Setting key Insertion Successful";
    }

    $this->loadModel('Vendors');
    $vendors = $this->Vendors->find()->where(['id !=' => 1])->all();
    $vendorSettings = [];
    foreach ($vendors as $key => $value) {
      $vendorSettings[] = ['vendor_id'=> $value->id,'setting_key_id' => $setting->id, 'value' => $defaultValue];
    }

    $vendorSettings = $this->Vendors->VendorSettings->newEntities($vendorSettings);

    if(!$this->Vendors->VendorSettings->saveMany($vendorSettings)){
      
      echo "Vendor Setting could not be Applied";
    
    }else{

      echo "Vendor Setting applied";
    }
  }

  public function updateVendorSettingValue($settingKeyId, $newValue, $vendorId = null){
    $this->loadModel('VendorSettings');
    if($vendorId){
      $settings = $this->VendorSettings->findByVendorId($vendorId)->where(['setting_key_id' => $settingKeyId])->all()->toArray();
    }

    $settings = $this->VendorSettings->find()->where(['setting_key_id' => $settingKeyId])->all()->toArray();

    foreach ($settings as $key => $value) {
      $settings[$key]->value = $newValue;
    }

    if($this->VendorSettings->saveMany($settings)){

      echo "Vendor Settings have been saved.";

    }else{
      echo "Vendor Settings could not be updated.";
      print_r($settings);
    }


  }
  


}