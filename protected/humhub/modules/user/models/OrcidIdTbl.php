<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "orcid_id_tbl".
 *
 * @property int $id
 * @property string $email
 * @property string $orcid_id
 */
class OrcidIdTbl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orcid_id_tbl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'orcid_id'], 'string', 'max' => 40],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'orcid_id' => 'Orcid ID',
        ];
    }
}
