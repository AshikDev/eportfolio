<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2019 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use yii\db\Migration;

/**
 * Class m190314_100808_authclient_add
 */
class m190314_100808_authclient_add extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('space_membership', 'authclient_id', $this->string(20)->defaultValue(new \yii\db\Expression('NULL')));
        $this->addColumn('group_user', 'authclient_id', $this->string(20)->defaultValue(new \yii\db\Expression('NULL')));

        $this->update('space_membership', ['authclient_id' => 'ldap'], ['added_by_ldap' => 1]);
        $this->update('group_user', ['authclient_id' => 'ldap'], ['added_by_ldap' => 1]);

        $this->dropColumn('space_membership', 'added_by_ldap');
        $this->dropColumn('group_user', 'added_by_ldap');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190314_100808_authclient_add cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190314_100808_authclient_add cannot be reverted.\n";

        return false;
    }
    */
}
