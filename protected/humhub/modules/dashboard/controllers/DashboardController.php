<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\dashboard\controllers;

use humhub\components\Controller;
use humhub\modules\dashboard\components\actions\DashboardStreamAction;
use humhub\modules\dashboard\components\actions\DashboardCommunityStreamAction;
use humhub\modules\dashboard\components\actions\DashboardProfileStreamAction;
use humhub\modules\stream\actions\ContentContainerStream;
use Yii;

class DashboardController extends Controller
{
    public function init()
    {
        $this->appendPageTitle(Yii::t('DashboardModule.base', 'Dashboard'));
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::class,
                'guestAllowedActions' => [
                    'index',
                    'stream'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => DashboardStreamAction::class,
                'activity' => false
            ],
            'cstream' => [
                'class' => DashboardCommunityStreamAction::class,
                'activity' => false
            ],
            'pstream' => [
                'class' => DashboardProfileStreamAction::class,
                'activity' => false
            ],
            'activity-stream' => [
                'class' => DashboardStreamAction::class,
                'activity' => true
            ]

        ];
    }

    /**
     * Dashboard Index
     *
     * Show recent wall entries for this user
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index_guest', []);
        } else {
            return $this->render('index', [
                'showProfilePostForm' => Yii::$app->getModule('dashboard')->settings->get('showProfilePostForm'),
                'contentContainer' => Yii::$app->user->getIdentity(),
                'commuityAll' => \humhub\modules\space\models\Space::find()->where(['community' => '_0_'])->all()
            ]);
        }
    }

}
