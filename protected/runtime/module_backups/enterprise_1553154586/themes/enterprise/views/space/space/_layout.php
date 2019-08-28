<?php
$space = $this->context->contentContainer;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\SpaceContent;
use humhub\widgets\FooterMenu;
use humhub\libs\Html;

?>


<div class="space-nav">
    <div class="container-fluid">
        <ul class="nav navbar-nav pull-left space-details">
            <li class="dropdown">
                <?php
                $currentSpace = null;
                if (Yii::$app->controller instanceof ContentContainerController && Yii::$app->controller->contentContainer instanceof Space) {
                    $currentSpace = Yii::$app->controller->contentContainer;
                }
                ?>
                <?php if ($currentSpace) { ?>
                    <?php
                    echo \humhub\modules\space\widgets\Image::widget([
                        'space' => $currentSpace,
                        'width' => 24,
                    ]);
                    ?>

                    <div class="space-title"> <?= Html::encode($currentSpace->name) ?> <space class="seperator"><i class="fa fa-angle-right"></i></space></div>
                <?php } ?>

            </li>

        </ul>

        <ul class="nav navbar-nav">
            <?php echo \humhub\modules\space\widgets\Menu::widget(['space' => $space]); ?>
        </ul>

        <div class="nav navbar-nav pull-right">

            <?php
            echo humhub\modules\space\widgets\HeaderControls::widget(['widgets' => [
                [\humhub\modules\space\widgets\InviteButton::className(), ['space' => $space], ['sortOrder' => 10]],
                [\humhub\modules\space\widgets\MembershipButton::className(), ['space' => $space], ['sortOrder' => 20]],
                [\humhub\modules\space\widgets\FollowButton::className(), ['space' => $space], ['sortOrder' => 30]]
            ]]);
            ?>

            <?php echo humhub\modules\space\modules\manage\widgets\Menu::widget(['space' => $space, 'template' => '@humhub/widgets/views/dropdownNavigation']); ?>
        </div>
    </div>
</div>

<div class="container-fluid space-layout-container">
    <div class="row space-content">
        <div class="col-md-<?= ($this->hasSidebar()) ? '9' : '12' ?> layout-content-container">
            <?= SpaceContent::widget(['contentContainer' => $space, 'content' => $content]) ?>
        </div>
        <?php if ($this->hasSidebar()): ?>
            <div class="col-md-3">
                <?= $this->getSidebar() ?>
                <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!$this->hasSidebar()): ?>
        <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_FULL_PAGE]); ?>
    <?php endif; ?>
</div>
