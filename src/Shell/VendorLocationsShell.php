<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Collection\Collection;
/**
 * VendorLocations shell command.
 */
class VendorLocationsShell extends Shell
{

    public function defaultLocations(){

        $this->loadModel('Vendors');
        $vendors = $this->Vendors->find()->where(['id !=' => 1])->contain('VendorLocations')->all();
        foreach ($vendors as $key => $value) {
            $this->out("In case for ".$value->org_name);
            if(!isset($value->vendor_locations) || !$value->vendor_locations){
                $this->out("No Locations found");
                $this->out(" ");
                continue;
            }
            $count = 0;
            $defaultLocationId = false;
            foreach ($value->vendor_locations as $key2 => $value2){
                if($value2->is_default && $count == 0){
                    $count++; 
                    $defaultLocationId = $value2->id;                   
                    $this->out("Default Location found & id is ".$value2->id);
                }else{

                    $value->vendor_locations[$key2]->is_default = false;
                }
            }
            if($count == 0){
                $value->vendor_locations[0]->is_default = true;
                $defaultLocationId = $value->vendor_locations[0]->id;
                $this->out("No default locations were set therefore location id ".$value->vendor_locations[0]->id." is made default");
            }
            $this->Vendors->VendorLocations->saveMany($value->vendor_locations);
            $this->out("Vendor Locations updated for this vendor");
            $this->_updateAutoReviewRequests($value->id, $defaultLocationId);
            $this->out(" ");
        }
        $this->out("Done");
    }

    private function _updateAutoReviewRequests($vendorId,$locationId){

        $this->loadModel('VendorLocations');
        $this->out("In case for updating auto review requests");
        $vendorLocations = $this->VendorLocations
                       ->findByVendorId($vendorId)
                       ->contain(['ReviewRequestStatuses' => function($q){
                         return $q->where(['ReviewRequestStatuses.user_id IS NULL', 'ReviewRequestStatuses.vendor_review_id IS NULL']);
                       }])
                       ->all()
                       ->toArray();
        
        $reviewRequests = (new Collection($vendorLocations))->extract('review_request_statuses')->unfold()->toArray();
        if(!$reviewRequests){
            $this->out("No review requests found");
            return;
        }
        $this->out("Review requests found");
        foreach ($reviewRequests as $key => $value) {
            $reviewRequests[$key]->vendor_location_id = $locationId; 
        }
        $this->VendorLocations->ReviewRequestStatuses->saveMany($reviewRequests);
        $this->out("Review Requests updated for this vendor");
    }
}
