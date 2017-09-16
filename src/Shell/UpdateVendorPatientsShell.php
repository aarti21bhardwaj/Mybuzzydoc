<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Network\Exception\BadRequestException;
use Cake\Core\Exception\Exception;

/**
 * UpdateVendorPatients shell command.
 */
class UpdateVendorPatientsShell extends Shell
{

    private $_token  = null;
    private $_vendorId = null;
    public function initialize()
    {
        parent::initialize();
        $this->_host = Configure::read('application.livePhUrl');
        $this->_sandboxUrl = Configure::read('application.phUrl');
    }


    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    public function fetchVendorsList(){
        $vendorsModel = TableRegistry::get('Vendors');
        $vendors = $vendorsModel->find()->contain('Users')->all()->toArray();
        // pr($vendors);

        foreach ($vendors as $vendor){
            if(!$vendor->people_hub_identifier && !$vendor->sandbox_people_hub_identifier){
                $this->out('The vendor '.$vendor->org_name.' doesnot have a peoplehub id');
                continue;
            }elseif($vendor->sandbox_people_hub_identifier){
                $this->out('This vendor is in sandbox mode. Going to fetch patient_name for vendor '.$vendor->org_name);
                $this->_updatePatientsName($vendor->id, $vendor->sandbox_people_hub_identifier, $vendor->users[0]->id, $this->_sandboxUrl);    
            }elseif(!$vendor->sandbox_people_hub_identifier && $vendor->people_hub_identifier){
                $this->out('This vendor is in live mode. Going to fetch patient_name for vendor '.$vendor->org_name);
                $this->_updatePatientsName($vendor->id, $vendor->people_hub_identifier, $vendor->users[0]->id, $this->_host);
            }
        }
    }

    private function _updatePatientsName($vendorId, $vendorPhId, $userId, $host){
        $vendorPatientsModel = TableRegistry::get('VendorPatients');
        $vendorPatients = $vendorPatientsModel->findByVendorId($vendorId)->where(['patient_name IS NULL'])->all();

        $vendorPatientsArr = (new Collection($vendorPatients))->combine('id', 'patient_peoplehub_id')->toArray();
        
        $this->out('The array for vendor patients is : '.json_encode($vendorPatientsArr));
        if(!count($vendorPatientsArr)){
            return;
        }
        $token = $this->getVendorToken($vendorPhId);
        
        if(!$token['status']){
            throw new Exception(__('TOKEN_ERROR'.json_encode($token)));
        }
        // pr($token);
        $token = $token['token'];
        
        $data=array();
        foreach ($vendorPatientsArr as $vendorPtId => $ptPhId) {
            //Set up Headers
            $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Bearer '.$token ]]);
            //setup request body in $data array
            $response = $http->get($host.'/api/vendor/users/'.$ptPhId);
            if(!$response->isOk()){
                throw new BadRequestException('Response is not okay.'.json_encode($response));
            }
            $response = json_decode($response->body());
            $ptName = $response->data->name;

            $data[] = ['id' => $vendorPtId, 'patient_name' => $ptName, 'user_id' => $userId];
        }
        $this->out('The data for pathEntity is: '.json_encode($data));
        $vendorPatients = $vendorPatientsModel->patchEntities($vendorPatients, $data);

        $temp = (new Collection($vendorPatients))->map(function($value, $key){
            if($value->errors()){
                return false;
            }else{
                return true;
            }
        })->toArray();
        
        if(in_array(0, $temp)){
            throw new Exception(__('ENTITY_NOT_CORRECT'));
        }

        $vendorPatients = $vendorPatientsModel->saveMany($vendorPatients);
        if(!$vendorPatients){
            throw new Exception(__('ENTITY_NOT_CORRECT'));
        }

        $this->out('BULK_DATA_SAVED');
    }

    public function  getVendorToken($vendorId){
      // $token = $this->_session->read('v_t');
      // if(!$this->_session->read('v_t')){
      $response = $this->_renewVendorToken($vendorId);
      if($response->isOk()){
        $response = json_decode($response->body());
        if($response->status){
          // $this->_session->write('v_t', $response->data->token);
          return ['status'=>true,'token'=>$response->data->token];
        }else{
          return $response;
        }
      }else{
        $err =array();
        $err['status']=false;
        $err['data']['message']='Unable to get vendor token.';
        $err['data']['data']=json_decode($response->body());
        return $err;
      }
      // }else{
      //  return ['status'=>true,'token'=>$token];
      // }
    }

    private function  _renewVendorToken($vendorId){

      $http = new Client(['headers' => ['Content-Type' => 'application/json','accept'=>'application/json','Authorization'=>'Basic '.base64_encode(Configure::read('reseller.client_id').':'.Configure::read('reseller.client_secret'))]]);
      $data = ['vendor_id' => $vendorId];
      $response = $http->post( $this->_host.'/api/vendor/token', json_encode($data) );
      return $response;
    }
}
