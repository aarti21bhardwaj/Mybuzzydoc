<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Date;

/**
 * Staff Report Shell Command
 */
class GiftCouponVendorSettingsShell extends Shell
{

	public function insertSetting(){

    $this->loadModel('SettingKeys');
    $setting = [
                  'name' => 'Gift Coupons',
                  'type' => 'boolean'
               ];

    $setting =  $this->SettingKeys->newEntity($setting);

    if(!$this->SettingKeys->save($setting)){
      
      echo "Insertion Failed";
    
    }else{

      echo "Insertion Successful";
    }

    $this->loadModel('Vendors');
    $vendors = $this->Vendors->find()->where(['id !=' => 1])->all();
    $vendorSettings = [];
    foreach ($vendors as $key => $value) {
      $vendorSettings[] = ['vendor_id'=> $value->id,'setting_key_id' => 12, 'value' => 1];
    }

    $vendorSettings = $this->Vendors->VendorSettings->newEntities($vendorSettings);

    if(!$this->Vendors->VendorSettings->saveMany($vendorSettings)){
      
      echo "Vendor Setting could not be Applied";
    
    }else{

      echo "Vendor Setting applied";
    }

  }

}