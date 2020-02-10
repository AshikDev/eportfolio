<?php $this->beginContent('@admin/views/layouts/main.php') ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('AdminModule.user', '<strong>Welcome Box</strong> administration'); ?>
    </div>
    <?= \humhub\modules\admin\widgets\WelcomeMenu::widget(); ?>

    <?= $content; ?>
</div>
<?php $this->endContent(); ?>