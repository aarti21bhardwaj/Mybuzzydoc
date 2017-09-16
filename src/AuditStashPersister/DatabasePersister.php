<?php

namespace App\AuditStashPersister;

use AuditStash\PersisterInterface;
use Cake\ORM\TableRegistry;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Credentials\CredentialProvider;
use Cake\Core\Configure;

class DatabasePersister implements PersisterInterface
{
    public function logEvents(array $auditLogs)
    {

        foreach ($auditLogs as $log) {
            $eventType = $log->getEventType();
            $data = [
                //'hello' => ['S' => 'this is some text'],
                'timestamp' => $log->getTimestamp(),
                'transaction' => $log->getTransactionId(),
                'type' => $log->getEventType(),
                'primary_key' => $log->getId(),
                'source' => $log->getSourceName(),
                'parent_source' => $log->getParentSourceName(),
                'original' => $eventType === 'delete' ? null : json_encode($log->getOriginal()),
                'changed' => $eventType === 'delete' ? null : json_encode($log->getChanged()),
                'meta' => json_encode($log->getMetaInfo())
            ];

            if($log->getParentSourceName() != '') {
				$data['parent_source'] =  $log->getParentSourceName();           	
            }

            $auditsTable = TableRegistry::get(Configure::read('AuditStash.tableName'));
            $data = $auditsTable->newEntity($data);
            $auditsTable->save($data);
            unset($data);
        }
    }
}


?>