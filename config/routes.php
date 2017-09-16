<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::prefix('api', function (RouteBuilder $routes) {

    $routes->connect('/checkVendorAccount',array('controller'=>'VendorRedeemedPoints', 'action'=>'isRedemptionPossible',"_method" => "POST"));
    $routes->connect('/users/update_password/',array('controller'=>'Users', 'action'=>'updatePassword',"_method" => "POST"));
    $routes->connect('/:controller',array('controller'=>':controller', 'action'=>'add',"_method" => "POST"));


    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'view',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+')
        );
    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'edit',"_method" => "PUT"), array('pass' => array('id'), 'id'=>'[\d]+')
        );
    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'delete',"_method" => "DELETE"), array('pass' => array('id'), 'id'=>'[\d]+')
        );

    $routes->connect('/users/update_password/:id',array('controller'=>'Users', 'action'=>'updatePassword',"_method" => "PUT"), array('pass' => array('id'), 'id'=>'[\d]+'));

    $routes->connect('/LegacyRedemptions/getPatientActivity/:id',array('controller'=>'LegacyRedemptions', 'action'=>'getPatientActivity',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+'));

    $routes->connect('/LegacyRedemptions/getStaffReport/',array('controller'=>'LegacyRedemptions', 'action'=>'getStaffReport',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+'));

     $routes->connect('/VendorMilestones/getRewardTypes/',array('controller'=>'VendorMilestones', 'action'=>'getRewardTypes',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+'));

        $routes->connect('/Awards/getRedeemerName/',array('controller'=>'Awards', 'action'=>'getRedeemerName',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+'));
    //routes for reviews

    $routes->connect('/:controller/notify', ['controller' => ':controller', 'action'=>'notify',  '_method' => 'POST']);

    $routes->connect('/:controller/request-review', ['controller' => ':controller', 'action'=>'requestReview',  '_method' => 'POST']);
    $routes->connect('/:controller/vendor-activity-report', ['controller' => ':controller', 'action'=>'vendorActivityReport',  '_method' => 'GET']);

    $routes->connect('/:controller/update-points', ['controller' => ':controller', 'action'=>'updatePoints',  '_method' => 'POST']);

    $routes->connect('/:controller/instant-award', ['controller' => ':controller', 'action'=>'instantAward',  '_method' => 'POST']);

    $routes->connect('/:controller/review', ['controller' => ':controller', 'action'=>'review',  '_method' => 'POST']);
    $routes->connect('/:controller/instantRedemption', ['controller' => ':controller', 'action'=>'instantRedemption',  '_method' => 'POST']);

    $routes->connect('/:controller/review-award', ['controller' => ':controller', 'action'=>'reviewAward',  '_method' => 'POST']);

    $routes->connect('/:controller/post-milestones', ['controller' => ':controller', 'action'=>'postMilestones',  '_method' => 'POST']);


     //route for fetching data for patientManagement

   Router::scope('/', function ($routes) {
    $routes->extensions(['json']);
    $routes->resources('api/VendorPromotions');
});

    /**
    *    routes for updating status in bulk
    **/
    $routes->connect('/:controller/bulk-update',array('controller'=>':controller', 'action'=>'bulkUpdate',"_method" => "PUT")
        );

    $routes->resources('LegacyRedemptions',
        ['map' =>
         [
            'bulkUpdate' =>
            [
                'action' => 'bulkUpdate',
                'method' => 'PUT'
            ],
         ]
        ]);

    $routes->connect('/:controller/ajax_template_data',array('controller'=>':controller', 'action'=>'ajaxTemplateData',"_method" => "POST")
        );
    $routes->resources('Referrals',
        ['map' =>
         [
            'ajaxTemplateData' =>
            [
                'action' => 'ajaxTemplateData',
                'method' => 'POST'
            ],
         ]
        ]);

// $routes->resources('Users',
//         ['map' =>
//          [
//             'updatePassword' =>
//             [
//                 'action' => 'updatePassword',
//                 'method' => 'PUT'
//             ],
//          ]
//         ]);
    // $routes->connect('/:controller/',array('controller'=>':controller', 'action'=>'edit',"[method]" => "Delete"));
    // $routes->connect('/:controller/:action/:id',['controller'=>':controller','action'=>':action' ],['id' => '\d+', 'pass' => ['id']]);
    $routes->fallbacks('InflectedRoute');
});

Router::prefix('patientPortalApis', function (RouteBuilder $routes) {
    $routes->connect('/LegacyRedemptions/getPatientActivity/',array('controller'=>'LegacyRedemptions', 'action'=>'getPatientActivity',"_method" => "GET"));

    $routes->connect('/:controller',array('controller'=>':controller', 'action'=>'add',"_method" => "POST"));

    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'view',"_method" => "GET"), array('pass' => array('id'), 'id'=>'[\d]+')
        );
    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'edit',"_method" => "PUT"), array('pass' => array('id'), 'id'=>'[\d]+')
        );
    $routes->connect('/:controller/:id',array('controller'=>':controller', 'action'=>'delete',"_method" => "DELETE"), array('pass' => array('id'), 'id'=>'[\d]+')
        );
    $routes->fallbacks('InflectedRoute');
});

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/patient-portal/:id/', ['controller' => 'PatientPortal', 'action'=>'view'], array('pass' => array('id'), 'id'=>'[\d]+'));


    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);


    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

Router::plugin('Integrateideas/Peoplehub', ['path' => '/integrateideas/peoplehub'],function($routes) {
    $routes->prefix('api', function($routes) {
        $routes->connect('/:controller/:action/*');
    });
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
