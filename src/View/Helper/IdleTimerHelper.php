<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;

class IdleTimerHelper extends Helper
{
    public $helpers = ['Html'];



    public function idleTime($idleTime)
    {	
    	$this->Html->scriptStart(['block' => 'idleTimeHelperScript']);
        	echo 'var idleTime = '.$idleTime.';';
        $this->Html->scriptEnd();
    }
}
?>