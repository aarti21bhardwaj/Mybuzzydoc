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
namespace App\Controller\PatientPortalApis;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Log\Log;

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


  //initialize auth
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
  }
  public function beforeFilter(Event $event)
  {

    //Log will keep the track of all the request and store it in logs/audit.log file
    Log::write('debug', $this->request);

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
  }

  public function beforeRender(Event $event)
    {
          //Log will keep the track of all the response and store it in logs/audit.log file
        //  Log::write('debug', $this->viewVars);
    }

}
