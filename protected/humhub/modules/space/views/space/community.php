<?php

/**
 * @var \humhub\modules\ui\view\components\View $this
 */
use humhub\modules\activity\widgets\ActivityStreamViewer;
use humhub\modules\post\widgets\Form;
use humhub\modules\space\modules\manage\widgets\PendingApprovals;
use humhub\modules\space\widgets\Members;
use humhub\modules\space\widgets\Sidebar;
use humhub\modules\stream\widgets\StreamViewer;

Yii::$app->view->params['spaceList'] = $spaceList;

$emptyMessage = '';
if ($canCreatePosts) {
    $emptyMessage = Yii::t('SpaceModule.views_space_index', '<b>This space is still empty!</b><br>Start by posting something here...');
} elseif ($isMember) {
    $emptyMessage = Yii::t('SpaceModule.views_space_index', '<b>This space is still empty!</b>');
} else {
    $emptyMessage = Yii::t('SpaceModule.views_space_index', '<b>You are not member of this space and there is no public content, yet!</b>');
}

//echo StreamViewer::widget([
//    'contentContainer' => $space,
//    'streamAction' => '/space/space/cstream',
//    'messageStreamEmpty' => $emptyMessage,
//    'messageStreamEmptyCss' => ($canCreatePosts) ? 'placeholder-empty-stream' : '',
//]);

echo humhub\modules\dashboard\widgets\DashboardContentCommunity::widget([
    'contentContainer' => $space,
]);

//echo humhub\modules\community\widgets\DashboardContent::widget([
//    'contentContainer' => $space
//]);


$this->beginBlock('sidebar');

echo Sidebar::widget(['space' => $space, 'widgets' => [
            [ActivityStreamViewer::class, ['contentContainer' => $space], ['sortOrder' => 10]],
            [PendingApprovals::class, ['space' => $space], ['sortOrder' => 20]],
            [Members::class, ['space' => $space], ['sortOrder' => 30]],
]]);

$this->endBlock();

?>
