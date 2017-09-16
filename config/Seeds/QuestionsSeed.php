<?php
use Migrations\AbstractSeed;

/**
 * Questions seed.
 */
class QuestionsSeed extends AbstractSeed
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
                  ['text' => 'Was patient on-time for appointment?','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient maintain good oral hygeine?','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient did not have any broken-brackets.','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Was the patient wearing their elastics/appliances?','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient come in for their routine dental check-up?','question_type_id' => '1','frequency' => '180','points' => '200','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Facial','question_type_id' => '1','frequency' => '1','points' => '150','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient had a Botox appointment.','question_type_id' => '1','frequency' => '1','points' => '225','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Filler (lines]','question_type_id' => '1','frequency' => '1','points' => '900','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Filler (Hollow Area]','question_type_id' => '1','frequency' => '1','points' => '1500','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient purchase new glasses?','question_type_id' => '1','frequency' => '1','points' => '500','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Is the patient coming in for cleaning?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Came in for Adjustment','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'On Track for Routine Visits','question_type_id' => '1','frequency' => '180','points' => '200','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Purchased Heartworm/Flea Meds','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Maintained Healthy Weight','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Is the patient following up with proper yearly shots?','question_type_id' => '1','frequency' => '365','points' => '150','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'On Track for Check-Up','question_type_id' => '1','frequency' => '180','points' => '200','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Has the patient purchased a new hearing aid?','question_type_id' => '1','frequency' => '1','points' => '3750','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient came in for Adjustment?','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Has the patient purchased batteries?','question_type_id' => '1','frequency' => '1','points' => '10','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Was patient on-time for appointment?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient maintain good oral hygeine?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient did not have any broken-brackets.','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Was the patient wearing their elastics/appliances?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient come in for their routine dental check-up?','question_type_id' => '1','frequency' => '180','points' => '250','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient follow a Good Brushing hygiene?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient has no Cavities','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Good Flossing','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Purchased New Glasses','question_type_id' => '1','frequency' => '1','points' => '750','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Came in for Cleaning','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient came in for Adjustment','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'On Track for Routine Visits','question_type_id' => '1','frequency' => '180','points' => '250','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Did the patient purchase Heartworm/Flea Medicines?','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Has the patient maintained a healthy weight?','question_type_id' => '1','frequency' => '1','points' => '50','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Yearly Shots','question_type_id' => '1','frequency' => '365','points' => '300','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'On Track for Check-Up','question_type_id' => '1','frequency' => '180','points' => '250','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Came in for Cleaning','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Came in for Adjustment','question_type_id' => '1','frequency' => '1','points' => '200','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Purchased Batteries','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'On Track for Routine Visits','question_type_id' => '1','frequency' => '60','points' => '250','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Was patient on-time for appointment?','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Is the patient following a good brushing hygiene?','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'No Cavities','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Is the patient following a Good Flossing hygiene?','question_type_id' => '1','frequency' => '1','points' => '100','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Was the patient on time for appointment?','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Good Brushing','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'The patient has no cavities.','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Good Flossing','question_type_id' => '1','frequency' => '1','points' => '25','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                  ['text' => 'Is the patient on track for routine check ups?','question_type_id' => '1','frequency' => '1','points' => '200','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')]
        ];

        $table = $this->table('questions');
        $table->insert($data)->save();
    }
}
