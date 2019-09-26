<?php

namespace humhub\modules\freichat\models;

use Yii;

/**
 * ConfigureForm defines the configurable fields.
 */
class ConfigureForm extends \yii\base\Model
{

    public $token;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['token', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('FreichatModule.base', 'FreiChat Token:'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'token' => Yii::t('FreichatModule.base', 'Token used to communicate with FreiChat server. Please do not edit this.'),
        ];
    }

}
