<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\space\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\components\behaviors\AccessControl;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\stream\actions\ContentContainerCStream;
use humhub\modules\space\widgets\Menu;
use humhub\modules\post\permissions\CreatePost;
use Yii;
use yii\web\HttpException;
use yii\db\Expression;

/**
 * SpaceController is the main controller for spaces.
 *
 * It show the space itself and handles all related tasks like following or
 * memberships.
 *
 * @property-read Space $contentContainer
 *
 * @author Luke
 * @package humhub.modules_core.space.controllers
 * @since 0.5
 */
class SpaceController extends ContentContainerController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'acl' => [
                'class' => AccessControl::class,
                'guestAllowedActions' => ['index', 'home', 'stream', 'community']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'stream' => [
                'class' => ContentContainerStream::class,
                'contentContainer' => $this->contentContainer
            ],
            'cstream' => [
                'class' => ContentContainerCStream::class,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }

    /**
     * Generic Start Action for Profile
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex() {
        $space = $this->getSpace();

        if (Yii::$app->request->get('tour') || Yii::$app->request->get('contentId')) {
            return $this->actionHome();
        }

        if (!$space->isMember()) {
            $defaultPageUrl = Menu::getGuestsDefaultPageUrl($space);
            if ($defaultPageUrl != null) {
                return $this->redirect($defaultPageUrl);
            }
        }

        $defaultPageUrl = Menu::getDefaultPageUrl($space);
        if ($defaultPageUrl != null) {
            return $this->redirect($defaultPageUrl);
        }

        if ($this->contentContainer->community != '_0_') {
            return $this->actionHome();
        } else {
            return $this->actionCommunity();
        }
    }

    /**
     * Default space homepage
     *
     * @return string the rendering result.
     * @throws \yii\base\InvalidConfigException
     */
    public function actionHome() {
        $space = $this->contentContainer;
        $canCreatePosts = $space->permissionManager->can(new CreatePost());
        $isMember = $space->isMember();

        $communityList = explode('_', trim($space->community, '_'));
        $community = Space::find()
                ->select(['name'])
                ->where(['in', 'id', $communityList])
                ->all();

        return $this->render('home', [
                    'space' => $space,
                    'canCreatePosts' => $canCreatePosts,
                    'isMember' => $isMember,
                    'community' => $community
        ]);
    }

    public function actionCommunity() {
        $this->subLayout = '_layout_community';

        $space = $this->contentContainer;
        $canCreatePosts = $space->permissionManager->can(new CreatePost());
        $isMember = $space->isMember();
        Yii::$app->session->setFlash('community_id',  $space->id);

        return $this->render('community', [
                    'space' => $space,
                    'canCreatePosts' => $canCreatePosts,
                    'isMember' => $isMember,
                    'spaceList' => $this->getMemberships(),
        ]);
    }

    // Custom
    protected function getMemberships()
    {
        if (!Yii::$app->user->isGuest) {
            return \humhub\modules\space\models\Membership::findByUserCommunity(Yii::$app->user->getIdentity(), $this->contentContainer->id)->all();
        }
    }
    // Custom

    /**
     * Follows a Space
     */
    public function actionFollow() {
        if (Yii::$app->getModule('space')->disableFollow) {
            throw new HttpException(403, Yii::t('ContentModule.controllers_ContentController', 'This action is disabled!'));
        }

        $this->forcePostRequest();
        $space = $this->getSpace();

        $success = false;

        if (!$space->isMember()) {
            // follow without notifications by default
            $success = $space->follow(null, false);
        }

        if (Yii::$app->request->isAjax) {
            return $this->asJson(['success' => $success]);
        }

        return $this->redirect($space->getUrl());
    }

    /**
     * Unfollows a Space
     */
    public function actionUnfollow() {
        $this->forcePostRequest();
        $space = $this->getSpace();

        $success = $space->unfollow();

        if (Yii::$app->request->isAjax) {
            return $this->asJson(['success' => $success]);
        }

        return $this->redirect($space->getUrl());
    }

    /**
     * Modal to  list followers of a space
     */
    public function actionFollowerList() {
        $query = User::find();
        $query->leftJoin('user_follow', 'user.id=user_follow.user_id AND object_model=:userClass AND user_follow.object_id=:spaceId', [':userClass' => Space::class, ':spaceId' => $this->getSpace()->id]);
        $query->orderBy(['user_follow.id' => SORT_DESC]);
        $query->andWhere(['IS NOT', 'user_follow.id', new Expression('NULL')]);
        $query->visible();

        $title = Yii::t('SpaceModule.base', '<strong>Space</strong> followers');

        return $this->renderAjaxContent(UserListBox::widget(['query' => $query, 'title' => $title]));
    }

}
