<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\widgets\SpaceChooserItem;

/** @var $typeMembershipMap array **/
/** @var $createSpaceTypes array **/

\humhub\modules\space\assets\SpaceChooserAsset::register($this);

$noSpaceView = '<div class="no-space"><i class="fa fa-dot-circle-o"></i><br>' . Yii::t('SpaceModule.widgets_views_spaceChooser', 'My spaces') . '<b class="caret"></b></div>';

$this->registerJsConfig('space.chooser', [
    'noSpace' => $noSpaceView,
    'remoteSearchUrl' => Url::to(['/enterprise/spacetype/browse/search-json']),
    'text' => [
        'info.remoteAtLeastInput' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'To search for other spaces, type at least {count} characters.', ['count' => 2]),
        'info.emptyOwnResult' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'No member or following spaces found.'),
        'info.emptyResult' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'No result found for the given filter.'),
    ],
]);

?>

<ul class="nav nav-pills nav-stacked nav-space-chooser" id="space-menu-dropdown">

    <li class="search">
        <form action="" class="dropdown-controls">
            <input type="text" id="space-menu-search"
                   class="form-control form-search"
                   autocomplete="off"
                   placeholder="<?= Yii::t('SpaceModule.widgets_views_spaceChooser', 'Filter'); ?>"
                   title="<?= Yii::t('SpaceModule.widgets_views_spaceChooser', 'Search for spaces'); ?>">

            <div class="search-reset" id="space-search-reset"><i class="fa fa-times-circle"></i></div>
        </form>
    </li>

    <?php foreach ($typeMembershipMap as $entry) : ?>
        <?php if(empty($entry['memberships']) && empty($entry['following']) && !in_array($entry['spaceType'], $createSpaceTypes)) {continue;} ?>
        <li class="title space-type-nav-title">
            <i class="fa fa-caret-up"></i>&nbsp;
            <?= Html::encode($entry['spaceType']->title); ?>
            <?php if (in_array($entry['spaceType'], $createSpaceTypes)) : ?>
                <span class="title-link">
                    <a href="#" data-action-click="ui.modal.load" aria-label="<?= Yii::t('SpaceModule.widgets_views_spaceChooser', 'Create new {spaceType}', ['spaceType' => Html::encode($entry['spaceType']->item_title)]) ?>" data-action-url="<?= Url::to(['/enterprise/spacetype/create-space/create', 'type_id' => $entry['spaceType']->id]) ?>">
                        <i class="fa fa-plus-square"></i>
                    </a>
                </span>
            <?php endif; ?>
        </li>
        <li>
            <div class="space-type-nav-container">
                <ul id="space-menu-type-<?= $entry['spaceType']->id ?>" class="space-entries">
                    <?php foreach ($entry['memberships'] as $membership): ?>
                        <?= SpaceChooserItem::widget(['space' => $membership->space, 'updateCount' => $membership->countNewItems(), 'isMember' => true]); ?>
                    <?php endforeach; ?>
                    <?php foreach ($entry['following'] as $followingSpace): ?>
                        <?= SpaceChooserItem::widget(['space' => $followingSpace, 'isFollowing' => true]); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<ul id="space-chooser-result" class="nav nav-pills nav-stacked nav-space-chooser space-entries">

</ul>
<ul class="nav nav-pills nav-stacked nav-space-chooser space-entries">
    <li id="space-menu-remote-search"></li>
</ul>
