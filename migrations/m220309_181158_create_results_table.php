<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%results}}`.
 */
class m220309_181158_create_results_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%results}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'score' => $this->integer()->defaultValue(0),
            'subject_id' => $this->integer(),
            'created_at' => $this->dateTime()->defaultValue(Date('Y-m-d H:i:s')),
            'updated_at' => $this->dateTime()->defaultValue(Date('Y-m-d H:i:s')),
        ]);

        //Foreign Key Constraint for User Id
        $this->addForeignKey('FK_user_result', 'results', 'user_id',   'users',  'id', 'CASCADE', 'CASCADE');
        //Foreign Key Constraint for Subject Id
        $this->addForeignKey('FK_user_subject', 'results', 'subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%results}}');
    }

}
