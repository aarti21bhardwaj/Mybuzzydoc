<?php
use Migrations\AbstractSeed;

/**
 * EmailSettings seed.
 */
class EmailSettingsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [

        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 1,
        'subject'=> 'Welcome to BuzzyDoc',
        'body'=> 'Hi {{first_name}},<br>Welcome to BuzzyDoc. You can now login to the application using the link below:<br /><br />{{link}}<br /><br />Your username is :  {{username}}<br />Your password is: Redacted for security purposes.<br /><br />You can always reach us at the details mentioned below.<br>Always happy to help, <br /><br />Elsie Hernandez,<br />BuzzyDoc Support Team',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 2,
        'subject'=> 'Clinic Review',
        'body'=> 
        'Hi {{patient_name}},<br /><br />If you want to earn {{points}} more points, click the link below to quickly leave us a review!!<br /> {{link}}<br /><br />Regards<br />{{clinic_name}}<br />{{address}}',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 3,
        'subject'=> 'Change Your Password',
        'body'=> 'Hi {{first_name}},<br /><br />Please click the following link to reset your password<br />{{link}}<br /><br />Regards<br />BuzzyDoc',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 4,
        'subject'=> '{{subject}}',
        'body'=> 'Hi {{name}}<br /><br />You have been referred to us by {{refer_from}}.<br />{{description}}.<br />Please click on the following link to fill the form {{link}}<br /><br />
Thanks & Regards<br /> BuzzyDoc',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 5,
        'subject'=> 'Payment Confirmation',
        'body'=> 'Hi {{first_name}}<br /><br />Thank you for the payment. Your credit card has been charged.<br />{{description}}.<br /><br />Your transactionId :{{transactionid}}.<br /><br /> Your Balance Deposited is :{{amount}}
            <br /><br />Reason for Credit Card Charge :{{reason}} <br /><br />
Thanks & Regards<br /> BuzzyDoc',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 6,
        'subject'=> 'Payment Confirmation',
        'body'=> 'Hi <br /><br />There was an error in charging your credit card.<br />{{description}}<br /><br />Your Vendor Id:{{vendor_id}}.<br /><br />
Thanks & Regards<br /> BuzzyDoc',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 7,
        'subject'=> 'Tier Changed',
        'body'=> 'Hi {{first_name}},<br /><br />Your tier has changed to {{tier_name}}<br /><br />Thanks & Regards<br /> {{name}}',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 8,
        'subject'=> 'Clinic Review',
        'body'=> 'Hi {{first_name}},<br /><br />You have completed one more year in our Tier Reward Program.<br /><br />Thanks & Regards<br /> {{name}}',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 9,
        'subject'=> 'Welcome {{name}}',
        'body'=> 'Hi {{name}},<br>Welcome to BuzzyDoc. You have been added by {{org_name}}.<br>You can now login to the application using the link below::<br /><br />{{link}}<br /><br />Your username is :  {{username}}<br />Your password is: {{password}}.<br /><br />You can always reach us at the details mentioned below.<br>Always happy to help, <br /><br />Elsie Hernandez,<br />BuzzyDoc Support Team',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 10,
        'subject'=> 'Reset BuzzyDoc Password Link - {{org_name}}',
        'body'=> "Hi {{patient_name}},<br><br>Please see your account information below:<br>Name: {{patient_name}}<br><br>Email: {{email}}<br><br>Username: {{username}}<br><br>You can use this information to login to your patient portal by clicking here!<br><br>{{ref}}<br><br>If you don't have password then reset your password by clicking here!<br><br>{{reset_link}}<br><br>Thanks,<br>The BuzzyDoc Team",
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 11,
        'subject'=> 'Instant Rewards',
        'body'=> 
        'Hi {{patient_name}},<br /><br />Click the link below to redeem your available instant rewards!!<br /> {{link}}<br /><br />Regards<br />{{org_name}}',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 12,
        'subject'=> 'Patient Self Sign Up',
        'body'=> 
        'Hi {{practice_name}}<br /><br />A patient just signed up through your patient portal. Details of the patient are:<br /><br /><strong>Name:</strong> {{patient_name}}<br /><strong>Email:</strong> {{patient_email}}<br /><br />Thanks<br />Elsie Harnandez',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 13,
        'subject'=> 'Redemption Notification',
        'body'=> 
        'Hi {{practice_name}}<br /><br />{{patient_name}}, a patient of your clinic just made a redemption. Details of which are as follows:<br /><br /><strong>Redemption Type :</strong> {{redemption_type}}<br /><strong>Points/Amount :</strong> {{points}}<br /><br />Thanks,<br />Elsie Hernandez',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ],
        [
        'email_layout_id' => 1,
        'email_template_id' => 1,
        'event_id' => 15,
        'subject'=> 'Thank you for redeeming!!',
        'body'=> 
        'Hi {{patient_name}},<br /> Congratulations! You just used your {{points}} BuzzyDoc points from {{practice_name}} for an (Amazon or Tango) gift card worth ${{amount}}. You should have received an email with your (Amazon or Tango) redemption code to your inbox after you redeemed. Please make sure that you retain this redemption code until you redeem it on (Amazon or tango).<br /><br />If you do not see it in your inbox you may need to check your junk/spam folder as they are sometimes filtered.<br /><br />If you do not find it in your Junk/spam folder do one of the following:<br />Log in to your rewards site and go to Activity history tab.<br />You can contact your practice to let them know what happened and they can easily resend the redemption code to your registered email id.<br />You can also create a ticket on BuzzyDoc at {{link}}<br /><br />If you have any questions please feel free to reach out to us at the contact information below.<br /><br  />Thanks,<br />Elsie Hernandez',
        'created' => date('Y-m-d H:i:s'),
        'modified'=> date('Y-m-d H:i:s')
        ]

        ];

        $table = $this->table('email_settings');
        $table->insert($data)->save();
    }
}