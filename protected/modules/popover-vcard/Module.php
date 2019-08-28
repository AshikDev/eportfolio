<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard;

use humhub\modules\popovervcard\models\Configuration;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{


    public $resourcesPath = 'resources';

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to([
            '/popover-vcard/admin'
        ]);
    }

    public function getName()
    {
        return Yii::t('PopoverVcardModule.base', 'VCard Popover');
    }

    public function getDescription()
    {
        return Yii::t('PopoverVcardModule.base', 'Shows a vcard popover in stream user and space links.');
    }


    /**
     * Returns the module configuration model
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        $model = new Configuration();
        $model->loadSettings();
        return $model;
    }

}
