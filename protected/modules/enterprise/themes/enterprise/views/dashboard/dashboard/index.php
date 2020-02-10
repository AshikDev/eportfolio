<?php
$this->pageTitle = Yii::t('DashboardModule.views_dashboard_index', 'Dashboard');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 layout-content-container">
            <?= \humhub\modules\dashboard\widgets\DashboardContent::widget([
                'contentContainer' => $contentContainer,
                'showProfilePostForm' => $showProfilePostForm
            ]) ?>
        </div>
        <div class="col-md-4 layout-sidebar-container">
            <?php
            echo \humhub\modules\dashboard\widgets\Sidebar::widget([
                'widgets' => [
                    [
                        \humhub\modules\activity\widgets\Stream::className(),
                        ['streamAction' => '/dashboard/dashboard/stream'],
                        ['sortOrder' => 150]
                    ]
                ]
            ]);
            ?>
            <?php if (version_compare(Yii::$app->version, '1.2.5', '>')): ?>
                <?= \humhub\widgets\FooterMenu::widget(['location' => \humhub\widgets\FooterMenu::LOCATION_SIDEBAR]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>