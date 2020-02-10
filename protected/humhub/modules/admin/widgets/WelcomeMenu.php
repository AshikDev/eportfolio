<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\admin\widgets;

use Yii;
use yii\helpers\Url;
use humhub\modules\admin\permissions\ManageUsers;
use humhub\modules\admin\permissions\ManageGroups;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\widgets\BaseMenu;

/**
 * User Administration Menu
 *
 * @author Basti
 */
class WelcomeMenu extends BaseMenu
{

    public $template = '@humhub/widgets/views/tabMenu';
    public $type = 'adminUserSubNavigation';

    public function init()
    {
        $this->addItem([
            'label' => Yii::t('AdminModule.views_user_index', 'View'),
            'url' => Url::to(['/admin/welcome/display']),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'admin' && (Yii::$app->controller->id == 'welcome'  && Yii::$app->controller->action->id == 'display')),
            'isVisible' => Yii::$app->user->can([
                new ManageUsers(),
                new ManageGroups(),
            ])
        ]);

        $this->addItem([
            'label' => Yii::t('AdminModule.views_user_index', 'Update'),
            'url' => Url::to(['/admin/welcome/edit']),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'admin' && Yii::$app->controller->id == 'welcome' && Yii::$app->controller->action->id == 'edit'),
            'isVisible' => Yii::$app->user->can([
                new ManageSettings()
            ])
        ]);

        parent::init();
    }

}
