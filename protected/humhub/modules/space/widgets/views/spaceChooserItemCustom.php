<?php
/* @var $space \humhub\modules\space\models\Space */

use humhub\modules\space\widgets\Image;
use humhub\libs\Helpers;
use yii\helpers\Html;
?>

<div<?= (!$visible) ? ' style="display:none"' : '' ?> data-space-chooser-item <?= $data ?> data-space-guid="<?= $space->guid; ?>">

    <a href="<?= $space->getUrl(); ?>">

        <div class="media">
            <?=
            Image::widget([
                'space' => $space,
                'width' => 24,
                'htmlOptions' => [
                    'class' => 'pull-left',
            ]]);
            ?>

            <div class="media-body">

                <strong class="space-name subspace_hover"><?= Html::encode($space->name); ?></strong>
                <?= $badge; ?>
                <div data-message-count="<?= $updateCount; ?>" style="display: none;" class="badge badge-space messageCount pull-right tt" title="<?= Yii::t('SpaceModule.widgets_views_spaceChooserItem', '{n,plural,=1{# new entry} other{# new entries}} since your last visit', ['n' => $updateCount]); ?>">
                    <?= $updateCount; ?>
                </div>
                <br>
                <p><?= Html::encode(Helpers::truncateText($space->description, 60)); ?></p>
            </div>
        </div>
    </a>

</div>

<?php
$this->registerCss(".subspace_hover:hover {
        color: #6fdbe8;
    }");
?>