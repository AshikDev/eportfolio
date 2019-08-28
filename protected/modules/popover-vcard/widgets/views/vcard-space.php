<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */


use humhub\libs\Html;
use humhub\modules\popovervcard\widgets\VCardAddons;
use humhub\modules\space\widgets\Image;
use yii\helpers\Url;

/* @var $this \humhub\components\View */
/* @var $space \humhub\modules\space\models\Space */

?>

<div class="vcardWrapper">
    <div class="vcardContent">
        <div class="vcardHeader"
             style="<?php if ($space->getProfileBannerImage()->hasImage()): ?> background-image: url(<?= $space->getProfileBannerImage()->getUrl(); ?>);<?php endif; ?>">
            <div class="headerContent">
                <div class="imageWrapper pull-left"><?= Image::widget(['space' => $space, 'width' => 60]); ?></div>
                <div class="displayName"><?= Html::encode($space->name); ?></div>
                <div class="title"><i
                            class="fa fa-users"></i> <?= Yii::t('PopoverVcardModule.base', '{count} members', ['count' => $memberCount]); ?>
                </div>
            </div>
        </div>
        <div class="vcardBody">
            <?= $description ?>

            <?= VCardAddons::widget(['container' => $space]); ?>
        </div>
        <div class="vcardFooter">
            <div class="pull-right">
                <a href="<?= Url::to(['/', 'container' => $space]); ?>"
                   class="btn btn-primary btn-sm"><?= Yii::t('PopoverVcardModule.base', 'Open space'); ?></a>
            </div>
        </div>
    </div>
</div>
