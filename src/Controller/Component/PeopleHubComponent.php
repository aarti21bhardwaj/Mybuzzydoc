<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Http\Client;
use Cake\Cache\Cache;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\Controller\Component\CookieComponent;
use Cake\Network\Session;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class PeopleHubComponent extends Component{
    //http://peoplehub.twinspark.co/bountee_sandbox
    private $_host = null;
    private  $_session = null;
    public function initialize(array $config){
        if(isset($config['liveMode']) && $config['liveMode']){
            $this->_host = Configure::read('application.livePhUrl');
        }else{
            $this->_host = Configure::read('application.phUrl');
        }

         if(isset($config['throwErrorMode']) && !$config['throwErrorMode']){
            $throwErrorMode = false;
        }else{
            $throwErrorMode = true;
        }
        $this->_session = new Session();
        $controller = new Controller;
         $this->Peoplehub = $controller->loadComponent('Integrateideas/Peoplehub.Peoplehub', [
        'clientId' => Configure::read('reseller.client_id'),
        'clientSecret' =>Configure::read('reseller.client_secret'),
        'apiEndPointHost' => $this->_host,
        'throwErrorMode' => $throwErrorMode,
        'liveApiEndPointHost' =>Configure::read('application.livePhUrl'),
      ]);
    }
    public function  getResellerToken(){
        $response = $this->Peoplehub->requestData('post', 'reseller', 'token');
        return $response;
    }

    public function  getResellerActivities($payload){
        $response = $this->Peoplehub->requestData('get', 'reseller', 'activities', false, $payload, false );
        return $response;
    }

    public function  getVendorToken($vendorId){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'token', false, false, false, $vendorId);
        return $response;
    }
    public function instantRedemption($instantRedemptionData, $vendorId){
    	$this->_checkRedemptionsAllowed($vendorId, $instantRedemptionData['user_id']);
        $response = $this->Peoplehub->requestData('post', 'vendor', 'UserInstantRedemptions', false, false, $instantRedemptionData, $vendorId);
        return ['status' => true, 'response' => $response];
    }
    public function provideReward($rewardCreditData, $vendorId){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'rewardCredits', false, false, $rewardCreditData, $vendorId);
        return ['status' => true, 'response' => $response];

    }

    private function _checkRedemptionsAllowed($vendorId, $patientId){

		$vendorsTable = TableRegistry::get('Vendors');
		$vendor = $vendorsTable->find()
							   ->where(['people_hub_identifier' => $vendorId])
							   ->orWhere(['sandbox_people_hub_identifier' => $vendorId])
							   ->contain(['VendorPatients' => function($q) use($patientId){
							   		return $q->where([' patient_peoplehub_id' => $patientId]);

							   }])
							   ->first();

		if(!$vendor || !isset($vendor->vendor_patient->redemptions) ||!$vendor->vendor_patient->redemptions){

			throw new ForbiddenException(__('Redemptions for this patient are disabled.'));
		}

	}

    public function redeemReward($rewardRedemptionData, $vendorId){
    	$this->_checkRedemptionsAllowed($vendorId, $rewardRedemptionData['user_id']);
        $response = $this->Peoplehub->requestData('post', 'vendor', 'redeemedCredits', false, false, $rewardRedemptionData, $vendorId);
        return ['status' => true, 'response' => $response];
    }
    /**
    * registerVendor method
    * This method hits PeopleHub add vendor api. Request body is created using the vendor data received.
    *
    * @param  Entity $vendorData Vendor Entity contains coressponding User Entity to be created.
    * @return PeopleHub Id generated at PeopleHub.
    * @author James Kukreja
    */
    public function registerVendor($vendorData)
    {
        $data=array();
        $data['name']=$vendorData->org_name;
        $data['vendor_contacts']['email']=$vendorData->users[0]['email'];
        $data['vendor_contacts']['phone']=$vendorData->users[0]['phone'];
        $data['vendor_contacts']['is_primary']=1;
        $data['vendor_reward_types'][0]['reward_method_id']=1;
        $data['vendor_reward_types'][0]['status']=1;
        $data['vendor_reward_types'][1]['reward_method_id']=2;
        $data['vendor_reward_types'][1]['status']=1;
        $data['vendor_reward_types'][2]['reward_method_id']=3;
        $data['vendor_reward_types'][2]['status']=1;

        $response = $this->Peoplehub->requestData('post', 'reseller', 'vendors', false, false, $data);
        return $response;

    }
    public function editVendor($vendorPeopleHubId,$vendorData){

        $response = $this->Peoplehub->requestData('put', 'vendor', 'vendors', $vendorPeopleHubId, false, $vendorData, $vendorPeopleHubId);

        return $response;
    }
    /**
    * registerVendor method
    * This method hits PeopleHub add vendor api. Request body is created using the vendor data received.
    *
    * @param  Entity $vendorData Vendor Entity contains coressponding User Entity to be created.
    * @return PeopleHub Id generated at PeopleHub.
    * @author James Kukreja
    */
    public function registerVendorOnLiveServer($vendorData)
    {
        $response = $this->Peoplehub->requestData('post', 'vendor', 'add-vendor-to-live', false, false, $vendorData, $vendorData['people_hub_identifier']);
        return $response;
    }
    public function getPatientActivity($vendorPeopleHubId,$userId){
        // pr($user); die;
        $payload = ['user_id' => $userId];
        $response = $this->Peoplehub->requestData('get', 'vendor', 'activities', $userId, false, $payload, $vendorPeopleHubId);
        return $response;
    }
    public function getStaffReport($vendorPeopleHubId){
        $response = $this->Peoplehub->requestData('get', 'vendor', 'activities', false, false, false, $vendorPeopleHubId);
        return $response;
    }
    public function getRedeemerName($vendorPeopleHubId){
        $response = $this->Peoplehub->requestData('get', 'vendor', 'activities', false, false, false, $vendorPeopleHubId);
        return $response;
    }
    public function search($vendorPeopleHubId, $query, $searchType=null){
        $payload = ['value' => $query, 'attributeType' => $searchType];
        $response = $this->Peoplehub->requestData('get', 'vendor', 'user-search', false, false, $payload, $vendorPeopleHubId);
        return $response;

    }
    public function registerPatient($vendorId, $registrationData){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'add-user', false, false, $registrationData, $vendorId);
        return $response;
    }
    public function searchPatient($patientData){
        $response = $this->Peoplehub->requestData('get', 'reseller', 'user-search', false, false, $patientData);
        return $response;
    }

    public function editPatient($vendorId, $patientPeoplehubId ,$profileData){
        $response = $this->Peoplehub->requestData('put', 'vendor', 'users', $patientPeoplehubId, false, $profileData, $vendorId);
        return $response;
    }
    public function patientDetails($vendorId, $patientsPeoplehubId){
        // $payload = ['user_id' => $patientsPeoplehubId];
        $response = $this->Peoplehub->requestData('get', 'vendor', 'users', $patientsPeoplehubId, false, false, $vendorId);
        return $response;
    }
    public function suggestUsername($vendorId, $data){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'suggest_username', false, false, $data, $vendorId);
        return $response;
    }
    public function resetPatientPasswordRequest($vendorId, $passwordResetData){
        // pr(' m here'); die;
        $response = $this->Peoplehub->requestData('post', 'user', 'forgot_password', false, false, $passwordResetData, $vendorId);
        return $response;
    }
    public function getMyCardSeries($vendorId){

        $response = $this->Peoplehub->requestData('get', 'vendor', 'vendor-card-series', false, false, false, $vendorId);
        return $response;
    }
    public function getMyCardSeriesInfo($vendorId,$id){

        $response = $this->Peoplehub->requestData('get', 'vendor', 'vendor-card-series', $id, false, false, $vendorId);
        return $response;
    }

    public function deletePatient($vendorId, $patientId){

        $response = $this->Peoplehub->requestData('delete', 'vendor', 'users', $patientId, false, false, $vendorId);
        return $response;
    }

    public function registerNewSeries($reqData){

        $response = $this->Peoplehub->requestData('post', 'reseller', 'reseller-card-series', false, false, $reqData);
        return ['status' => true, 'response' => $response->data];
    }

    public function getResellerSeries(){
        $response = $this->Peoplehub->requestData('get', 'reseller', 'reseller-card-series', false, false);
        return ['status' => true, 'response' => $response->data];
    }
    public function uploadUsers($vendorId,$reqData){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'upload-users', false, false, $reqData, $vendorId);
        return $response;
    }
    public function issueVendorCards($reqData){
        $response = $this->Peoplehub->requestData('post', 'reseller', 'vendor-cards', false, false, $reqData);
        return $response;
    }
  	public function getVendorActivity($vendorId,$reqData=null){
          $response = $this->Peoplehub->requestData('get', 'vendor', 'activities', false, false, $reqData, $vendorId);
        return $response;
      }
    public function bulkReward($vendorId,$reqData=null){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'bulk-reward', false, false, $reqData, $vendorId);
        return $response;
    }
    public function rollbackAwards($vendorId, $reqData = null){
        $response = $this->Peoplehub->requestData('post', 'vendor', 'reverse-credit', false, false, $reqData, $vendorId);
        return $response;
    }
}
?>
