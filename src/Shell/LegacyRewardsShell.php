<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Utility\Xml;

/**
 * LegacyRewards shell command.
 */
class LegacyRewardsShell extends Shell
{   

    public function initialize(){
        $controller = new Controller;
        $this->ApiAmazon = $controller->loadComponent('ApiAmazon');
        
        //Mapping of Old Amazon Ids & New Ids
        $this->updateArray = [

            'BT00CTOYI4' => 'BT00CTP4CE',
            'B00746W5EM' => 'B00746W5EM',
            'B001BA527O' => 'B001BA527O',
            'B008L0HMIY' => 'B00FF2Z1IY',
            'B00G4BTH9Y' => 'B00G13H8GY',
            'B00FF2XK0K' => 'B00DPK11XM',
            '1476745374' => '1476745374',
            'B00EOWAZAA' => 'B00DHOHH6Q',
            'B00G2E5ZUI' => 'B016V6W5D6',
            'B00CTL5J3Q' => 'B00KSA5L88'
        ];

        //These product prices were not accessible through Amazon Apis or list price was returned in Item Attributes
        $this->updatedProductPrices = [
            'B004D1OBFW' => 48.65,
            'B008CS5QTW' => 178.88,
            'B008EQ1ZNS' => 248.79,             
            'B004T47HKE' => 21.95,
            'B00008W72D' => 59.99, 
            '0877798753' => 9.70, 
            'B00FF2Z1IY' => 26.75,
            'B00G13H8GY' => 8.49,
            'B007M7U1TO' => 336.64,
            'B00DPK11XM' => 19.75,
            'B0001EMM0G' => 108.99,
            '1223063151' => 16.97,
            'B00YBZREGI' => 44.40,
            'B000CBSNKQ' => 49.99,
        ];
    }

    public function updateAmazonIds(){

        $updateArray = $this->updateArray;

        $count = 0;

        $this->loadModel('LegacyRewards');

        foreach ($updateArray as $key => $value) {
            
            $rewards = $this->LegacyRewards->findByAmazonId($key)->all();

            if($rewards){
                
                foreach($rewards as $reward) {
                    $reward->amazon_id = $value;
                    $this->LegacyRewards->save($reward);
                    $this->out($key.' updated to '.$value.' for reward id '.$reward->id);
                    $count++;                    
                }

            }
        }

        $this->out($count.' rewards affected.');
    }

    public function updateProductPrices(){

        $updatedArray = $this->updateArray;

        $productIds = [

            'B00DGEUOVC', 'B007JCMDA2', 'B007RFEM32', 'BT00CTOYI4', 'BT00CTOYSY', 'B007RFELYW', 'B00AVMTQAC', 'B00746W5EM', 'B00746W9F2', 'B004D1OBFW', 'B008CS5QTW', 'B008EQ1ZNS', 'B007NPC6H0', 'B005M7URHC', 'B00CI6J8KW', '1419711326', 'B004T47HKE', 'B001BA527O', 'B00BOAKJKQ', 'B00B1LN8WY', 'B0035YAG3Y', 'B007Q0OEI6', 'B004478GGK', '075669812X', 'B0019R4VE4', 'B00008W72D', '0877798753', 'B00000ISUU', 'B007TIE0GQ', 'B000ZDME7Y', 'B008L0HMIY', 'B000PWNGV8', 'B000ESWFA6', 'B000NQNS5K', 'B00G4BTH9Y', 'B0000DEW80', 'B004BPVF7W', 'B00GURKS0Y', 'B001I912SQ', 'B007M7U1TO', 'B00EPEALEM', 'B003TUCB82', 'B000A7OUE0', 'B0013J5QQ0', 'B00AR51Y5I', 'B004P5PD9O', 'B00FF2XK0K', '1476745374', 'B0001EMM0G', '0375842209', '0385751532', '0756686067', 'B004FZY16A', '1223063151', '148231780X', 'B005D1A87G', 'B0038N09LG', 'B00EOWAZAA', 'B0033SJZJ8', 'B00HLAO738', 'B0078EP9S4', 'B00DNAGTIQ', 'B00F2RZPOM', 'B00BXLTVFU', 'B00BXQIKDE', 'B0092KBPOE', 'B00GOLH8PY', 'B009VA4N5I', 'B00G2E5ZUI', 'B00G5L867C', 'B0085RZLQ8', 'B0081MQ47C', 'B00CTL5J3Q', 'B00I8T7DVE', 'B0029DDJEK', 'B00GDQ0RMG', 'B00SU65SD0', 'B005PPD1GA', 'B00MCHH1AQ', 'B009VWM8YO', 'B00F6P3DTO', 'B002KW3OQS', 'B00YBZREGI', 'B007J3FA8I', 'B001W0J17K', 'B00O7Q9D6K', 'B006SLYAUQ', 'B00OKIWTM0', 'B00ZE360GG', 'B00390T5JA', 'B018Y227MY', 'B017B1BXQU', 'B003TWVIL6', 'B01BPGL8TY', 'B00Y8YOEU6', 'B008B7BC8S', 'B001WAKFDY', 'B000CBSNKQ', 'B000BYTOHA', 'B000FK3WDC', 'B002XNS7V6', 'B00EAHXYD4', 'B006BC3K3A', 'B011XO54MA', 'BT00CTOY20', 'B00BXLVAQS'
        ];

        $this->count = 0;

        $this->LegacyRewards = $this->loadModel('LegacyRewards');
        $this->newFile = fopen('AmazonErrors.txt', 'w');
        foreach ($productIds as $key => $value) {
            $itemId = $value;
            if(isset($updatedArray[$value])){
                $itemId = $updatedArray[$value];
            }
            $price = $this->getPrice($itemId);
            if($price){
                $this->updatePoints($itemId, $price);
            }
        }
        fclose($this->newFile);
        $this->out($this->count.' rewards affected.');
        
    }

    public function getPrice($itemId){

        if(isset($this->updatedProductPrices[$itemId])){
            return $this->updatedProductPrices[$itemId];
        }

        $options = array(
            'Operation' => 'ItemLookup',
            'ItemId' => $itemId,
            'ResponseGroup' => 'ItemAttributes');

        $request = $this->ApiAmazon->aws_signed_request('com', $options, Configure::read('AWS.key'), Configure::read('AWS.secret'), Configure::read('AWS.associate_tag'));
            
        $response = @file_get_contents($request);
        
        if(!$response){
            $this->out("Error in item look up for amazon id". $itemId."No Response from Amazon. Trying Again..");
            $x = false;
            while(!$x){
                $response = @file_get_contents($request);
                if(!$response){
                    $this->out("Still No Response, Trying Again");
                    
                }else{
                    $x = true;
                    $this->out("Got It!");
                }
            }
        }

        $response = Xml::toArray(Xml::build($response));
        if(!$response['ItemLookupResponse']['Items']['Request']['IsValid']){
            $this->out($response);
            return false;
        }

        if(isset($response['ItemLookupResponse']['Items']['Request']['Errors'])){
            $this->out("Error in item look up for amazon id". $itemId);
            print_r($response['ItemLookupResponse']['Items']['Request']['Errors']);
            fwrite($this->newFile, $itemId.', "'.$response['ItemLookupResponse']['Items']['Request']['Errors']['Error']['Message'].'"'."\n");
            return false;
        }

        if(isset($response['ItemLookupResponse']['Items']['Item']['ItemAttributes']['ListPrice']['Amount'])){
            $price = $response['ItemLookupResponse']['Items']['Item']['ItemAttributes']['ListPrice']['Amount'] / 100;
            $this->out('price of item id '.$itemId.' is '.$price);
            return $price;
        }

        $this->out('List Price not set for amazon id '.$itemId);
        fwrite($this->newFile, $itemId.' , "List Price not set"'."\n");
        print_r($response);
        return false;


    }

    public function updatePoints($amazonId, $price){
        
        $pointsValue = (int) Configure::read('pointsValue');
        $points = ceil($price) * $pointsValue;
        $rewards = $this->LegacyRewards->findByAmazonId($amazonId)->all()->toArray();
        if($rewards){
            foreach ($rewards as $reward) {
                $reward->points = $points;
                $this->LegacyRewards->save($reward);
                $this->out('Points of reward with id '.$reward->id.' & amazon id '.$amazonId.' updated to '.$points);
                $this->count = $this->count+1; 
            }
            return;
        }
        $this->out('Reward with amazon id '.$amazonId.' not found in database ');

    }

    
}
