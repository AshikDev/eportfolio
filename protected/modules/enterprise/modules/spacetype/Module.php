<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\modules\spacetype;

use Yii;
use yii\helpers\Url;
use humhub\modules\enterprise\modules\spacetype\models\Type;

/**
 * Space Types
 *
 * @author Luke
 */
class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'humhub\modules\enterprise\modules\spacetype\controllers';

    public static function onAdminSpaceMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('EnterpriseModule.spacetype', 'Types'),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'spacetype' && Yii::$app->controller->id == 'admin'),
            'url' => Url::to(['/enterprise/spacetype/admin/index']),
        ));
    }

    public static function onDirectoryMenuInit($event)
    {
        $event->sender->deleteItemByUrl(Url::to(['/directory/directory/spaces']));

        $i = 0;

        $query = Type::find()->andWhere(['show_in_directory' => 1])->addOrderBy(['sort_key' => SORT_ASC]);

        foreach ($query->all() as $type) {
            $i++;
            $event->sender->addItem(array(
                'label' => $type->title,
                'sortOrder' => 300 + $i,
                'group' => 'directory',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'spacetype' && Yii::$app->controller->id == 'directory' && Yii::$app->request->get('id') == $type->id),
                'url' => Url::to(['/enterprise/spacetype/directory/index', 'id' => $type->id]),
            ));
        }
    }

    public static function onSpaceAdminDefaultMenuInit($event)
    {

        $event->sender->addItem(array(
            'label' => Yii::t('EnterpriseModule.spacetype', 'Image'),
            'sortOrder' => 290,
            'isActive' => (Yii::$app->controller->id == 'space-admin' && Yii::$app->controller->action->id == 'image'),
            'url' => $event->sender->space->createUrl('/enterprise/spacetype/space-admin/image'),
        ));

        if (Type::find()->count() > 1) {
            $event->sender->addItem(array(
                'label' => Yii::t('EnterpriseModule.spacetype', 'Type'),
                'sortOrder' => 300,
                'isActive' => (Yii::$app->controller->id == 'space-admin' && Yii::$app->controller->action->id == 'index'),
                'url' => $event->sender->space->createUrl('/enterprise/spacetype/space-admin/index'),
            ));
        }
    }

    /**
     * Add type_id to attributes
     */
    public static function onSpaceSearchAdd($event)
    {
        $event->attributes['type_id'] = $event->sender->space_type_id;
    }

    public static function onSpaceBeforeInsert($event)
    {
        $space = $event->sender;

        if ($space->space_type_id == "") {
            $type = Type::find()->orderBy(['sort_key' => SORT_ASC])->one();
            $space->space_type_id = $type->id;
        }
    }

    public static function onSpaceChooserCreate($event)
    {
        // Switch to Enterprise Space Chooser
        $event->config['class'] = widgets\Chooser::className();
    }

    public static function onSpaceChooserItemCreate($event)
    {
        $event->config['class'] = widgets\SpaceChooserItem::className();
    }

}
