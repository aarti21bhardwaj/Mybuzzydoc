<?php 

namespace App\AuditStashPersister\Traits;

use App\AuditStashPersister\DynamoDbPersister;
use Cake\Utility\Inflector;
use App\Model\Audit\AuditTrail;
use Cake\Event\Event;

trait AuditLogTrait
{
	public function fetchAuditLogs($timeRange = null){
		//Fetch name of the entity from the entity object
		$awsClient = new DynamoDbPersister();
		$tableName = Inflector::underscore($this->source());
	    $awsItems = $awsClient->getAuditLogs($tableName, $timeRange);
		return $awsItems; 
	}

	public function saveMany($entities, $options = [])
    {
        $isNew = [];

        $trail = new AuditTrail();
        $trailOptions = $trail->toSaveOptions();

        foreach ($trailOptions as $key => $value) {
           $options[$key] = $value;
        }

        $return = $this->connection()->transactional(
            function () use ($entities, $options, &$isNew, $trail) {
                foreach ($entities as $key => $entity) {
                    $isNew[$key] = $entity->isNew();
                    if ($this->save($entity, $options) === false) {
                        return false;
                    }
                }
            }
        );

        if ($return === false) {
            foreach ($entities as $key => $entity) {
                if (isset($isNew[$key]) && $isNew[$key]) {
                    $entity->unsetProperty($this->primaryKey());
                    $entity->isNew(true);
                }
            }
            return false;
        }

        if ($return !== false) {
            $event = new Event('Model.afterCommit', $this);
		    $this->behaviors()->get('AuditLog')->afterCommit($event, $entities[0], $trail->toSaveOptions());
		}
        return $entities;
    }
}

?>