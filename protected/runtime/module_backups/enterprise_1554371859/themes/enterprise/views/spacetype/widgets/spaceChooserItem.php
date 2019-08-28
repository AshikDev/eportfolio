<?php
/* @var $space \humhub\modules\space\models\Space */

use yii\helpers\Html;

?>
<li role="presentation" class="<?= ($isCurrentSpace) ? 'active' : '' ?>" data-space-chooser-item <?= $data ?>
    data-space-guid="<?= $space->guid; ?>" data-space-type="<?= $spaceTypeID ?>">
    <a href="<?= $space->getUrl(); ?>">
        <div class="media">
            <div class="media-left">
                <!-- Show space image -->
                <?=
                \humhub\modules\space\widgets\Image::widget([
                    'space' => $space,
                    'width' => 24,
                    'htmlOptions' => [
                        'class' => 'pull-left',
                        'style' => 'border: 2px solid ' . $space->color . ';',
                    ]
                ]);
                ?>
            </div>
            <div class="media-body">
                <div data-message-count="<?= $updateCount; ?>" style="display:none;"
                     class="badge badge-space bounceIn animated messageCount pull-right"><?= $updateCount; ?></div>
                <?php if ( $data === 'data-space-archived' || $data === 'data-space-following') : ?>
                    <?= $badge ?>
                <?php elseif($data === 'data-space-none') : ?>
                    <i class="fa fa-search badge-space pull-right type tt" title="<?= Yii::t('EnterpriseModule.widgets_spaceChooserItem', 'Your are not a member of this space') ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <div class="space-entry-title">

                    <?= Html::encode($space->name); ?>

                </div>
            </div>
        </div>
    </a>
</li>
