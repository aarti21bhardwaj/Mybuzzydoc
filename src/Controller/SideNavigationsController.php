<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
/**
 * SideNavigations Controller
 *
 * @property \App\Model\Table\SideNavigationsTable $SideNavigations
 */
class SideNavigationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $sideNavigations = $this->paginate($this->SideNavigations);

        $this->set(compact('sideNavigations'));
        $this->set('_serialize', ['sideNavigations']);
    }

    /**
     * View method
     *
     * @param string|null $id Side Navigation id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sideNavigation = $this->SideNavigations->get($id, [
            'contain' => []
        ]);

        $this->set('sideNavigation', $sideNavigation);
        $this->set('_serialize', ['sideNavigation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sideNavigation = $this->SideNavigations->newEntity();
        if ($this->request->is('post')) {
            $sideNavigation = $this->SideNavigations->patchEntity($sideNavigation, $this->request->data);
            if ($this->SideNavigations->save($sideNavigation)) {
                $this->Flash->success(__('The side navigation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The side navigation could not be saved. Please, try again.'));
        }
        $this->set(compact('sideNavigation'));
        $this->set('_serialize', ['sideNavigation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Side Navigation id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sideNavigation = $this->SideNavigations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sideNavigation = $this->SideNavigations->patchEntity($sideNavigation, $this->request->data);
            if ($this->SideNavigations->save($sideNavigation)) {
                $this->Flash->success(__('The side navigation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The side navigation could not be saved. Please, try again.'));
        }
        $this->set(compact('sideNavigation'));
        $this->set('_serialize', ['sideNavigation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Side Navigation id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sideNavigation = $this->SideNavigations->get($id);
        if ($this->SideNavigations->delete($sideNavigation)) {
            $this->Flash->success(__('The side navigation has been deleted.'));
        } else {
            $this->Flash->error(__('The side navigation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function referrals(){

    }

    public function awardingPoints(){

    }

    public function programs(){

    }
     public function rewards(){
        
    }
    public function practice(){
        
    }
    public function reports(){
        // pr($this->request->data); die;
        if($this->request->is('post')){
            if(isset($this->request->data['optionsRadios'])){
                $data = array_values($this->request->data)[1];
                $getValue = array_values($this->request->data);
                $fromToDateRangeArray = explode(" - ",$getValue[0]);
                // pr($data); die;
            } else {
                $data = array_keys($this->request->data)[0];
                $getValue = array_values($this->request->data);
                $fromToDateRangeArray = explode(" - ",$getValue[0]);
            }
    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fromToDateRangeArray[0])) {
            $from = $fromToDateRangeArray[0];
            $to = isset($fromToDateRangeArray[1]) ? $fromToDateRangeArray[1] : null;
        }
     else {
      $this->Flash->error(__('Please enter a valid date range')); 
      return $this->redirect(['action' => 'reports']);
     }

      $redirections = [         'point_history'             => ['controller' => 'Reports', 'action'                                                    => 'pointsHistoryReport'],

                                'redemptions'               => ['controller' => 'Reports', 'action' => 'redemptions'],

                                'review'                    => ['controller' => 'ReviewRequestStatuses', 
                                                                'action'=> 'index'],

                                'referrals'                 => ['controller' => 'Referrals', 'action'                           => 'index'],

                                'referral_leads'            => ['controller' => 'ReferralLeads', 'action'                           => 'index'],

                                'patient_self_registration' => ['controller' => 'Reports', 'action' =>                            'selfSignUp'],

                                'c_charges'                 => ['controller' => 'CreditCardCharges', 
                                                                'action' =>'index']
        ];
        foreach ($redirections as $key => $value) {
            if($data == $key){
                $url = 
               $this->redirect(['controller' => $value['controller'], 'action' => $value['action'], 'from'=>$from, 'to' =>$to]);
            }
        }
    }
    }
}
