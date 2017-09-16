<?php 
namespace App\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Cake\log\log;

class MailgunTransport extends AbstractTransport
{
    public function send(Email $email)
    {

    	echo $email.'this is email'
    	//echo "here"; die('qqq');
        // Do something.
        /*$yourInstance = $email->transport('your')->transportClass();
		$yourInstance->myCustomMethod();
		$email->send();*/
    }
}
?>

