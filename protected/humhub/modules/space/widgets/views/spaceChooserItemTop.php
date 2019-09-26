<?php

/* @var $space \humhub\modules\space\models\Space */

use humhub\modules\space\widgets\Image;
use humhub\libs\Helpers;
use yii\helpers\Html;
?>

<li<?= (!$visible) ? ' style="display:none"' : '' ?> data-space-chooser-item <?= $data ?> data-space-guid="<?= $space->guid; ?>" <?= ($space->community !== '_0_') ? 'style="padding-left: 30px;"' : ''; ?>>
    <a href="<?= $space->getUrl(); ?>">
        <div class="media">
            <?= Image::widget([
                'space' => $space,
                'width' => 24,
                'htmlOptions' => [
                    'class' => 'pull-left',
            ]]);
            ?>
            <div class="media-body">
                <strong class="space-name" <?= ($space->community == '_0_') ? 'style="font-size: 18px;"' : ''; ?>><?= Html::encode($space->name); ?></strong>
                    <?= $badge ?>
                <div data-message-count="<?= $updateCount; ?>" style="display: none;" class="badge badge-space messageCount pull-right tt" title="<?= Yii::t('SpaceModule.widgets_views_spaceChooserItem', '{n,plural,=1{# new entry} other{# new entries}} since your last visit', ['n' => $updateCount]); ?>">
                    <?= $updateCount; ?>
                </div>
                <br>
            </div>
        </div>
    </a>
</li>
