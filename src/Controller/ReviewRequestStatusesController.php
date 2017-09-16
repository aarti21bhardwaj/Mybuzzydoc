<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Routing\Router;
use Cake\I18n\Date;
/**
 * ReviewRequestStatuses Controller
 *
 * @property \App\Model\Table\ReviewRequestStatusesTable $ReviewRequestStatuses
 */
class ReviewRequestStatusesController extends AppController
{

     /*
    *
    * @type
    * This variable is defined for the defining conditions for specifying a vendor
    *user 
    */
    protected $_vendorCondition;

    public function initialize(){
        parent::initialize();
        // $this->Auth->config('authorize', ['Controller']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('post')|| $this->request->is('put')){
            $fromToDateRangeArray = explode(" - ",$this->request->data['daterange']);
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $from = new Date($from);
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
            } else {
            $this->Flash->error(__('Please enter a valid date range')); 
            return $this->redirect(['action' => 'index']);
            }
        } else{   
            $from =isset($_GET['from']) ? $_GET['from'] : null;
            $from = new Date($from);
            $to =isset($_GET['to']) ? $_GET['to'] : null;
            $to = new Date($to);
            $to = $to->addDays(1);
        }
        
        $loggedInUser = $this->Auth->user();
        if($loggedInUser['role']->name == self::SUPER_ADMIN_LABEL){
            $reviewRequestStatuses = $this->ReviewRequestStatuses->find()->contain(['Users', 'VendorReviews', 'VendorLocations'])->where($this->_vendorCondition)->where(['ReviewRequestStatuses.created >=' => $from,
                                'ReviewRequestStatuses.created <' => $to,
                                ])->limit(2000)->order(['ReviewRequestStatuses.created' => 'DESC'])->all();
            $users = $this->ReviewRequestStatuses->Users->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'first_name')->toArray();
        }else{
            $reviewRequestStatuses = $this->ReviewRequestStatuses->find()->contain(['VendorReviews', 'VendorLocations'])->where(['VendorLocations.vendor_id' => $this->Auth->user('vendor_id')])->where(['ReviewRequestStatuses.created >=' => $from,
                                'ReviewRequestStatuses.created <' => $to,
                                ])->limit(2000)->order(['ReviewRequestStatuses.created' => 'DESC'])->all();
            $users = $this->ReviewRequestStatuses->Users->findByVendorId($this->Auth->user('vendor_id'))->all()->combine('id', 'first_name')->toArray();
            
        }
        $this->set(compact('reviewRequestStatuses', 'users'));
        $this->set('_serialize', ['reviewRequestStatuses', 'users']);
    }
    
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reviewRequestStatus = $this->ReviewRequestStatuses->get($id);
        if ($this->ReviewRequestStatuses->delete($reviewRequestStatus)) {
            $this->Flash->success(__('The review request status has been deleted.'));
        } else {
            $this->Flash->error(__('The review request status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * requestReview method
     * This method is temporary for genearting a review request. It just Sets the View.
     * 
     */
    public function requestReview(){
       
        $reviewRequestStatus = null;
        $this->set('reviewRequestStatus', $reviewRequestStatus);
    }

    /**
     * givePoints method
     * This method is temporary and will be shifted to apis. Points to be awarded by staff admin will lead to Award      * points event being fired.
     * 
     */
    public function givePoints($id=null, $string=null)
    {
        
        pr('Points will be given here');die;
    }

    public function isAuthorized($user)
    {
      
        $action = $this->request->params['action'];
        if (in_array($action, ['requestReview', 'index'])) {
            return true;
        }
    }
}
?>