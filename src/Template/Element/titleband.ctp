<?php 
use Cake\Network\Session;
$session = new Session();
$this->VendorSettings = $session->read('VendorSettings');
$this->Auth = $session->read('Auth');
//Inflecting the names of the controller
$underscore = \Cake\Utility\Inflector::underscore($this->request->params['controller']);
$humanize = \Cake\Utility\Inflector::humanize($underscore);

$underscoreAction = \Cake\Utility\Inflector::underscore($this->request->params['action']);
$humanizeAction = \Cake\Utility\Inflector::humanize($underscoreAction);
                
if($humanize == 'Review Request Statuses'){
    $humanize = 'Review Requests';
    }
if($humanize == 'Legacy Rewards'){
    $humanize = 'Rewards';
    }
if($humanize == 'Vendor Promotions'){
    $humanize = 'Practice Promotions';
}
if($humanize == 'Vendor Redemption History'){
    $humanize = 'Practice Redemption History';
    }    
if($humanize == 'Vendor Deposit Balances'){
    $humanize = 'Practice Deposit Balances';
    }
if($humanize == 'Vendor Milestones'){
    $humanize = 'Practice Milestones';
    }
if($humanize == 'Vendor Survey Questions'){
    $humanize = 'Practice Survey Questions';
    }
if($humanize == 'Vendor Referral Settings'){
    $humanize = 'Practice Referral Settings';
    }
if($humanize == 'Vendor Email Settings'){
    $humanize = 'Practice Email Settings';
    }
if($humanize == 'Vendor Locations'){
    $humanize = 'Practice Locations';
    }
if($humanize == 'Vendor Documents'){
    $humanize = 'Documents';
    }
if($humanize == 'Vendor Florist Settings'){
    $humanize = 'Sending Flowers';
    }
if($humanize == 'Vendor Florist Orders'){
    $humanize = 'Approve Floral Orders';
    }
if(($humanize == 'Side Navigations') && ($underscoreAction != 'reports')){
    $humanize =  $humanizeAction ;
    }
if(($humanize == 'Side Navigations') && ($underscoreAction == 'reports')){
    $humanize = 'Reports' ;
    }
if($humanize == 'Legacy Redemptions'){
    $humanize = 'Redemptions';
    }
if($humanize == 'Vendor Cards'){
    $humanize = 'Practice Cards';
    }
if($humanize == 'Vendor Card Requests'){
    $humanize = 'Card Requests';
    }
if($humanize == 'Vendors'){
    $humanize = 'Clients';
    }
if($humanize == 'Vendor Instant Gift Coupon Settings'){
    $humanize = 'Instant Rewards Settings';
    }
if(($humanize == 'Sending Flowers') && ($humanizeAction == 'Add')){
    $humanizeAction = 'Sending Flowers';
    } 
if(($humanize == 'Practice Cards') && ($humanizeAction == 'Index')){
    $humanizeAction = 'Practice Cards';
    } 
if(($humanize == 'Card Requests') && ($humanizeAction == 'Index')){
    $humanizeAction = 'Card Requests';
    }  
if(($humanize == 'Approve Floral Orders') && ($humanizeAction == 'Index')){
    $humanizeAction = 'Approve Floral Orders';
    // pr($underscoreAction); die;
    }
            ?>
<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-10">
            <?php if($humanize == 'Users' && $humanizeAction == 'Dashboard'){
                    echo '<h2 id="pageTitle">Dashboard</h2>';
                }else{
            ?>
            <h2 id="pageTitle"><?= $humanize ?></h2>
            <?php }
                if(!($humanize == 'Users' && $humanizeAction == 'Dashboard')){
            ?>
        
        <ol class="breadcrumb" id="breadcrumb">
            <li>
            <?php if($this->Auth['User']['role']->name == 'admin'){ ?>
                
                <a href="<?=$this->Url->build(["controller" => "users","action" => "index"]);?>">Home</a>
            <?php } else{ ?>
                <a href="<?=$this->Url->build(["controller" => "users","action" => "dashboard"]);?>#/">Home</a>
              <?php  } ?>
            </li>
            <li>
                <?php 
                    $avoidArray = ["Vendor Settings", "Reports", "Instant Rewards Settings"];
                    if($humanize == 'Assessment Survey Questions' && ($humanizeAction == 'Index')){
                        $humanize = 'Assessment Surveys';
                        echo $this->Html->link($humanize,"/".'AssessmentSurveys');

                    }elseif($this->Auth['User']['role']->name != 'admin' && $humanize == 'Promotions'){
                        $humanize = "Practice Promotions";
                        echo $this->Html->link($humanize,"/".'VendorPromotions');

                    }elseif(!in_array($humanize, $avoidArray)){
                       
                        if($humanize == $humanizeAction) {
                            echo 'Settings';
                        }else { 
                           echo $this->Html->link($humanize,"/".$this->request->params['controller']); 
                        }
                    }else{ 
                        echo $humanize;
                     } 
                ?>
            </li>
            <?php if(!($humanizeAction == 'Index') && ($humanize != 'Reports')){ ?>
            <li class="active">
                <strong><?= $humanizeAction; //ucfirst($this->request->params['action']) ?></strong>
            </li>
            <?php } ?>
        </ol>
        <?php } ?>
    </div>
</div>