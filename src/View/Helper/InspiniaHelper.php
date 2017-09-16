<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;

class InspiniaHelper extends Helper
{
    public $helpers = ['Html'];

    public function horizontalRule()
    {
        return '<div class="hr-line-dashed"></div>';
    }
}
