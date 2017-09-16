<?php

namespace App\AuditStashPersister;

use AuditStash\PersisterInterface;
use Cake\ORM\TableRegistry;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Credentials\CredentialProvider;
use Cake\Core\Configure;

class DynamoDbPersister implements PersisterInterface
{

    protected $_dynamoDbClient;
    protected $_tableName;

    public function __construct(){

        $profile = Configure::read('DynamoDb.Profile');
        $path = Configure::read('DynamoDb.CredentialsFilePath');

        $provider = CredentialProvider::ini($profile, $path);
        $provider = CredentialProvider::memoize($provider);
        $this->_tableName = Configure::read('DynamoDb.AuditsTableName');
        $region = Configure::read('DynamoDb.Region');


        $this->_dynamoDbClient = new DynamoDbClient([
            'region'  => $region,
            'version' => 'latest',
            'credentials' => $provider
        ]);
    }

    public function logEvents(array $auditLogs)
    {

        $client = $this->_dynamoDbClient;
        $tableName = $this->_tableName;

        foreach ($auditLogs as $log) {
            $eventType = $log->getEventType();
            $data = [
                 'event_timestamp' => ['S' => $log->getTimestamp()],
                 'log_timestamp' => ['N' => (string) time()],
                 'transaction_id' => ['S' => $log->getTransactionId()],
                 'event_type' => ['S' => $log->getEventType()],
                 'primaryKey' => ['N' => (string)$log->getId()],
                 'source_table' => ['S' => $log->getSourceName()],
                 'original' => ['S' => $eventType === 'delete' ? '{"message":"record deleted"}' :json_encode($log->getOriginal())],
                 'changed' => ['S' => $eventType === 'delete' ? '{"message":"record deleted"}' : json_encode($log->getChanged())],
                 'meta' => ['S' => json_encode($log->getMetaInfo())],
            ];

            if($log->getParentSourceName() != '') {
                $data['parent_source'] = ['S' => $log->getParentSourceName()];              
            }

            $response = $client->putItem([
                'TableName' => $tableName,
                'Item' => $data,
                'ReturnConsumedCapacity' => 'TOTAL'
            ]);
        }
    }

    public function getAuditLogs($source , integer $timeRange = null){
        
        $client = $this->_dynamoDbClient;
        $tableName = $this->_tableName;

        if(!$timeRange) {
            $timeRange = time() - (24*60*60);            
        }

        $response = $client->query([
            'TableName' => $tableName,
            'KeyConditionExpression' => 'source_table = :v_source_table and log_timestamp >= :v_log_timestamp',
            'ExpressionAttributeValues' =>  [
                ':v_source_table' => ['S' => $source],
                ':v_log_timestamp' => ['N' => (string)$timeRange]
            ],
            'ConsistentRead' => true
        ]);

        foreach ($response['Items'] as &$item) {
            foreach($item as $key => &$value) {
                if(isset($value['N'])) {
                 $value = (integer)$value['N'];   
                }

                if(isset($value['S'])) {
                    if($this->isJson($value['S'])) {
                        $value = json_decode($value['S']);
                    }else {
                        $value = (string)$value['S'];                        
                    }
                }
            }
        }
        //run the query to dynamodb and fetch the results and return
        return $response['Items'];

    }

    public function isJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
    }
}

?>