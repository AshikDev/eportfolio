<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\assets\AppAsset;
use humhub\modules\enterprise\themes\enterprise\assets\EnterpriseThemeAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
EnterpriseThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $this->pageTitle; ?></title>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php $this->head() ?>
        <?= $this->render('head'); ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div id="wrapper">

            <div id="sidebar-wrapper">
                <?php echo \humhub\widgets\SiteLogo::widget(); ?><br>
                <?php echo \humhub\widgets\TopMenu::widget(); ?>
                <div id="hide-sidebar">
                    <a href="#menu-toggle" class="menu-toggle" class="dropdown-toggle" aria-label="<?= Yii::t('EnterpriseModule.base', 'Hide sidebar') ?>">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <?php echo \humhub\modules\enterprise\modules\spacetype\widgets\Chooser::widget(); ?>
            </div>

            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-md">

                            <div id="topbar-first" class="topbar">
                                <div id="rp-nav" class="nav pull-left">
                                    <ul class="nav pull-left navigation-bars">

                                        <li class="dropdown">
                                            <a href="#menu-toggle" class="menu-toggle" aria-label="<?= Yii::t('EnterpriseModule.base', 'Show sidebar') ?>" class="dropdown-toggle">
                                                <i class="fa fa-bars"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="menu-seperator"></div>
                                </div>
                                <?= \humhub\modules\enterprise\widgets\SearchWidget::widget(); ?>
                                <div class="topbar-actions pull-right">

                                    <ul class="nav pull-left" id="search-menu-nav">
                                        <?php echo \humhub\widgets\TopMenuRightStack::widget(); ?>
                                    </ul>

                                    <div class="menu-seperator"></div>
                                    <div class="notifications">
                                        <?=
                                        \humhub\widgets\NotificationArea::widget(['widgets' => [
                                                [\humhub\modules\notification\widgets\Overview::className(), [], ['sortOrder' => 10]],
                                        ]]);
                                        ?>
                                    </div>

                                    <div class="menu-seperator"></div>

                                    <?= \humhub\modules\user\widgets\AccountTopMenu::widget(['showUserName' => false]); ?>
                                </div>
                            </div>

                            <div class="content">
                                <?= $content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>