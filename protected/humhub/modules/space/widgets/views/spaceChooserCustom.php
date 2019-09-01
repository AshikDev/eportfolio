<?php
/* @var $this \humhub\components\WebView */
/* @var $currentSpace \humhub\modules\space\models\Space */

use humhub\modules\space\assets\SpaceChooserAsset;
use humhub\modules\space\widgets\SpaceChooserItemCustom;
use yii\helpers\Url;
// Custom
//use humhub\modules\space\widgets\Menu;

SpaceChooserAsset::register($this);

$noSpaceView = '<div class="no-space"><i class="fa fa-dot-circle-o"></i><br>' . Yii::t('SpaceModule.widgets_views_spaceChooser', 'My spaces') . '<b class="caret"></b></div>';

$this->registerJsConfig('space.chooser', [
    'noSpace' => $noSpaceView,
    'remoteSearchUrl' => Url::to(['/space/browse/search-json']),
    'text' => [
        'info.remoteAtLeastInput' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'To search for other spaces, type at least {count} characters.', ['count' => 2]),
        'info.emptyOwnResult' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'No member or following spaces found.'),
        'info.emptyResult' => Yii::t('SpaceModule.widgets_views_spaceChooser', 'No result found for the given filter.'),
    ],
]);
?>

<div class="panel panel-default spaces" id="new-spaces-panel_<?= $community->id; ?>">

    <!-- Display panel menu widget -->
    <?php echo humhub\widgets\PanelMenu::widget(['id' => 'new-spaces-panel_' . $community->id]); ?>

    <div class="panel-heading">
        <a href="<?= $community->getUrl(); ?>">
            <?php echo Yii::t('DirectoryModule.base', '<strong>' . $community->name . '</strong>'); ?>
        </a>
    </div>
    <div class="panel-body">

        <?php foreach ($memberships as $membership) : ?>
            <?=
            SpaceChooserItemCustom::widget([
                'space' => $membership->space,
                'updateCount' => $membership->countNewItems(),
                'isMember' => true
            ]);
            ?>
            
            <?//= Menu::widget(['space' => $membership->space]); ?>

        <?php endforeach; ?>

        <?php foreach ($followSpaces as $followSpace) : ?>
            <?=
            SpaceChooserItemCustom::widget([
                'space' => $followSpace,
                'isFollowing' => true
            ]);
            ?>
        <?php endforeach; ?>

    </div>
</div>
