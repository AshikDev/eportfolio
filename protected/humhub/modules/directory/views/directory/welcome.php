<div class="panel panel-default groups">

    <div class="panel-heading">
        <?= Yii::t('DirectoryModule.base', '<strong>Welcome</strong> Box'); ?>
    </div>

    <div class="panel-body">
        <?php echo humhub\widgets\MarkdownView::widget(['markdown' => $welcomeModel->content]); ?>
        <hr>
    </div>

</div>
