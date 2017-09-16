<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\Log\Log;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Network\Session;
use Cake\Utility\Inflector;


/**
 * Vendors Controller
 *
 * @property \App\Model\Table\VendorsTable $Vendors
 */
class VendorCardsController extends AppController
{
  public function initialize(){
    parent::initialize();
    // $this->Auth->config('authorize', ['Controller']);
    $this->Auth->allow(['setUpWizard']);
  }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
      $this->loadModel('VendorSettings');
      $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
      ->contain(['SettingKeys' => function($q){
        return $q->where(['name' => 'Live Mode']);
      }])->first()->value;
      $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
      $myCardSeries = $this->PeopleHub->getMyCardSeries($this->Auth->user('vendor_peoplehub_id'));
      $loggedInUser = $this->Auth->user();
      $this->set('loggedInUser', $loggedInUser);
      $this->set('myCardSeries', $myCardSeries->data);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function view($id)
    {
      $this->loadModel('VendorSettings');
      $liveMode = $this->VendorSettings->findByVendorId($this->Auth->user('vendor_id'))
      ->contain(['SettingKeys' => function($q){
        return $q->where(['name' => 'Live Mode']);
      }
      ])
      ->first()->value;
      $this->loadComponent('PeopleHub', ['liveMode' => $liveMode]);
      $myCardSeries = $this->PeopleHub->getMyCardSeriesInfo($this->Auth->user('vendor_peoplehub_id'),$id);
      $loggedInUser = $this->Auth->user();
      $this->set('loggedInUser', $loggedInUser);
       $this->set('myCardSeries', $myCardSeries->data);
    }

  }
