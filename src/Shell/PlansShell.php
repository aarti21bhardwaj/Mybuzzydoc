<?php
namespace App\Shell;

use Cake\Console\Shell;

/**
 * Plans shell command.
 */
class PlansShell extends Shell
{

    
    public function insertFeature($name){
        $this->loadModel('Features');
        $feature = $this->Features->newEntity(['name' => $name]);
        if($this->Features->save($feature))
        {
            echo 'Feature inserted'; die;
        }
        else{
            echo 'Error in inserting feature'; die;
        }
    }

    public function insertNewPlanFeatures(){
        $this->loadModel('PlanFeatures');
        $planFeatures = [

                            [
                                'plan_id' => 3,
                                'feature_id'=> 3
                            ],
                [
                                'plan_id' => 1,
                                'feature_id'=> 15
                            ],
                            [
                                'plan_id' => 2,
                                'feature_id'=> 15
                            ],
                            [
                                'plan_id' => 3,
                                'feature_id'=> 15
                            ],
                        ];

        $planFeatures = $this->PlanFeatures->newEntities($planFeatures);
        
        if($this->PlanFeatures->saveMany($planFeatures))
        {
            echo 'PlanFeatures inserted'; die;
        }
        else{
            pr($planFeatures);
            echo 'Error in inserting PlansFeatures'; die;
        }
    }
}
