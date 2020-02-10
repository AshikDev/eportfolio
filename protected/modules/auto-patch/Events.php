<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\autopatch;


use Yii;
use yii\helpers\Url;

class Events
{

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('AutoPatchModule.base', 'HumHub Patches'),
            'url' => Url::to(['/auto-patch/admin']),
            'icon' => '<i class="fa fa-bug"></i>',
            'group' => 'manage',
            'sortOrder' => 90000,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'auto-patch')
        ));
    }
}