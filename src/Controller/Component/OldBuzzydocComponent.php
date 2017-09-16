<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;

/**
 * OldBuzzydoc component
 */
class OldBuzzydocComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getResults($sql){

    	$host = Configure::read('oldBuzzyDoc.host');
    	$username = Configure::read('oldBuzzyDoc.username');
    	$password = Configure::read('oldBuzzyDoc.password');
    	$database = Configure::read('oldBuzzyDoc.database');

	    ConnectionManager::drop('my_connection');
	    $config = [
	      'className' => 'Cake\Database\Connection',
	      'driver' => 'Cake\Database\Driver\Mysql',
	      'persistent' => false,
	      'host' => $host,
	      'username' => $username,
	      'password' => $password,
	      'database' => $database,
	      'encoding' => 'utf8',
	      'timezone' => 'UTC',
	      'flags' => [],
	      'cacheMetadata' => true,
	      'log' => true,
	      'quoteIdentifiers' => false,
	      'url' => env('DATABASE_URL', null),
	    ];

	    ConnectionManager::config('my_connection', $config);
	    $conn = ConnectionManager::get('my_connection');
	    $response = $conn->execute($sql);

	    return $response;
  	}
}
