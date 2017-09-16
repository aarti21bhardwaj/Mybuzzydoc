<?php
use Migrations\AbstractSeed;

/**
 * QuestionTypes seed.
 */
class QuestionTypesSeed extends AbstractSeed
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
                            'name'=> 'Boolean',
                            
                        ],
                        [
                            'name'=> 'Text',
                            
                        ],
        ];

        $table = $this->table('question_types');
        $table->insert($data)->save();
    }
}
