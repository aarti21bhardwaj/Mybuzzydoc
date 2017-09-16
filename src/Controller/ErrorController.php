<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Controller;

use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * Error Handling Controller
 *
 * Controller used by ErrorHandler to render error views.
 */
class ErrorController extends Controller
{

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(){    
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event){

    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        //if debug mode is off shoot the email for errors
        //var_dump(Configure::check('errors-email')); die;
        if (Configure::read('debug') == false && Configure::check('errors-email')) {
            $time = Time::now();
            $email = new Email('default');
            $email->to(Configure::read('errors-email'))
                  ->subject($time.'-Error Logs For BuzzyDoc')
                  ->send($this->viewVars);
        }
        $this->viewBuilder()->templatePath('Error');

    }

}
