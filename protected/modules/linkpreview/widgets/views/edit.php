<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $options [] */
/* @var $model humhub\modules\linkpreview\models\LinkPreview */

humhub\modules\linkpreview\assets\Assets::register($this);
?>

<?= Html::beginTag('div', $options) ?>
        <div class="media">
            <div class="media-left media-image">
                <?php if ($model->isNewRecord) : ?>
                    <div class="image-controls text-center" style="display:none;">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" data-action-click="previous" class="btn btn-default btn-sm btn-prev-thumbnail">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button type="button" data-action-click="next" class="btn btn-default btn-sm btn-next-thumbnail">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="help-block">
                            <?= Yii::t('LinkpreviewModule.base', 'Choose a thumbnail'); ?>
                            <br>(<span class="current">1</span> of <span class="total">1</span>)
                        </div>
                    </div>
                <?php elseif($model->image): ?>
                    <img class="media-object" rendered="true" src="<?= Html::encode($model->image) ?>" style="width: 80px;">
                <?php endif; ?>
            </div>

            <div class="media-body">
                <h4 class="media-heading">
                    <div class="text preview-title-text"><?= Html::encode($model->title); ?></div>
                    <div class="input">
                        <?= Html::activeTextInput($model, 'title', ['class' => 'form-control title-input', 'style' => 'display:none']); ?>
                    </div>
                </h4>
                <div class="help-block preview-url-text">
                    <?= Html::encode($model->url) ?>
                </div>
                <div class="preview-description">
                    <div class="text preview-description-text"><?= Html::encode($model->description); ?></div>
                    <div class="input">
                        <?= Html::activeTextarea($model, 'description', ['class' => 'form-control description-input', 'rows' => '4', 'style' => 'display:none'])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-remove">
            <i class="fa fa-times"></i>
        </div>

    <?= Html::activeHiddenInput($model, 'url', ['class' => 'form-control url-input']) ?>
    <?= Html::activeHiddenInput($model, 'image', ['class' => 'form-control image-input']) ?>

<?= Html::endTag('div'); ?>