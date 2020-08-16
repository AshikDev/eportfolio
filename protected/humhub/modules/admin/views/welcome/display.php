<div class="panel-heading">
    <?= Yii::t('DirectoryModule.base', '<strong>Welcome</strong> Box'); ?>
</div>

<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <?php echo humhub\widgets\MarkdownView::widget(['markdown' => $welcomeModel->content]); ?>
        </div>
    </div>

</div>