<?php
 use yii\helpers\Html;

 humhub\modules\linkpreview\assets\Assets::register($this);

 /* @var $linkPreview \humhub\modules\linkpreview\models\LinkPreview */
?>

<?= Html::beginTag('div', $options)?>
    <hr style="margin-top:0px">
    <div class="media">
        <div class="media-left media-image">
            <a href="<?= $linkPreview->url; ?>" target="_blank">
                <?php if ($linkPreview->getImageUrl() != "") : ?>
                    <img class="media-object" alt="80x80" rendered="true" src="<?= $linkPreview->getImageUrl(); ?>" style="width: 80px;">
                <?php endif; ?>
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">
                <a href="<?= $linkPreview->url; ?>" target="_blank">
                    <?= $linkPreview->title; ?>
                </a>
            </h4>

            <p class="help-block">
                <a href="<?= $linkPreview->url; ?>" target="_blank"><?= $linkPreview->url; ?></a>
            </p>

            <div class="description"><?= $linkPreview->description; ?></div>
        </div>
    </div>
<?= Html::endTag('div') ?>