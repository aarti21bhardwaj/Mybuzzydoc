<?php
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link      http://cakephp.org CakePHP(tm) Project
* @since     0.2.9
* @license   http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Log\Log;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use AuditStash\Meta\RequestMetadata;
use Cake\Event\EventManager;

/**
* Application Controller
*
* Add your application-wide methods in the class below, your controllers
* will inherit them.
*
* @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
*/
class ApiController extends Controller
{
  // public function isAuthorized($user)
  // {
  //     return false;
  // }

  /*
   * Constant Defined for the admin levels and can be accessed in any controller
   */
   const SUPER_ADMIN_LABEL = 'admin';
   const STAFF_ADMIN_LABEL = 'staff_admin';
   const STAFF_MANAGER_LABEL = 'staff_manager';

  //initialize auth
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
   // $this->loadComponent('Flash');
    $this->loadComponent('Auth', [
      'authorize' => ['Custom' => [] ],
      'authError' => 'You have been automatically logged out of your dashboard. Please use valid credentials to log back in.',
    ]);
    $this->Auth->config('unauthorizedRedirect', false);
    //$this->Auth->config('loginRedirect', false);
    //$this->Auth->config('authorize', false);
  }
  public function beforeFilter(Event $event)
  {

    //Log will keep the track of all the request and store it in logs/audit.log file
    Log::write('debug', $this->request);

    $user  = $this->Auth->user();

    if($user && $user['idle_timer'] && $this->request->url != 'users/lockscreen'){

        $session = $this->request->session();
        $lastSeen = $session->read('lastSeen');
        $currentTime = Time::now();
        $currentTime = strtotime($currentTime);
        $idleTime = Configure::read('idleTime');
        // pr($currentTime);pr("    ");pr($lastSeen);pr($currentTime - $lastSeen."    ".$idleTime/1000);die;
        if(($currentTime - $lastSeen) >= ($idleTime/1000)){

            throw new ForbiddenException(__('You have exceeded the maximum idle time allowed.'));
        
        }else{

            $session->write('lastSeen', $currentTime);
        }
    }

    $origin = $this->request->header('Origin');
    if($this->request->header('CONTENT_TYPE') != "application/x-www-form-urlencoded; charset=UTF-8"){
          $this->request->env('CONTENT_TYPE', 'application/json');
    }
    $this->request->env('HTTP_ACCEPT', 'application/json');
    if (!empty($origin)) {
      $this->response->header('Access-Control-Allow-Origin', $origin);
    }

    if ($this->request->method() == 'OPTIONS') {
      $method  = $this->request->header('Access-Control-Request-Method');
      $headers = $this->request->header('Access-Control-Request-Headers');
      $this->response->header('Access-Control-Allow-Headers', $headers);
      $this->response->header('Access-Control-Allow-Methods', empty($method) ? 'GET, POST, PUT, DELETE' : $method);
      $this->response->header('Access-Control-Allow-Credentials', 'true');
      $this->response->header('Access-Control-Max-Age', '120');
      $this->response->send();
      die;
    }
    // die;
    $this->response->cors($this->request)
    ->allowOrigin(['*'])
    ->allowMethods(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'])
    ->allowHeaders(['X-CSRF-Token','token'])
    ->allowCredentials()
    ->exposeHeaders(['Link'])
    ->maxAge(300)
    ->build();

    //Meta data for Id and IP of the logged in User for auditing.
    EventManager::instance()->on(new RequestMetadata($this->request, $this->Auth->user('id')));
  }

    public function beforeRender(Event $event)
    {
          //Log will keep the track of all the response and store it in logs/audit.log file
        //  Log::write('debug', $this->viewVars);
    }

}
