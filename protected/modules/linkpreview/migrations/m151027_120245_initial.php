<?php

use yii\db\Schema;
use yii\db\Migration;

class m151027_120245_initial extends Migration
{

    public function up()
    {
        $this->createTable('linkpreview', [
            'id' => Schema::TYPE_PK,
            'class' => Schema::TYPE_STRING . ' CHARACTER SET utf8',
            'pk' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING,
            'url' => Schema::TYPE_STRING,
            'image' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT,
        ]);
        $this->createIndex('fk_linkpreview', 'linkpreview', ['class', 'pk'], true);
    }

    public function down()
    {
        echo "m151027_120245_initial cannot be reverted.\n";

        return false;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
