<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\popovervcard\widgets\VCardAddons;
use humhub\modules\user\widgets\Image;
use yii\helpers\Url;

/* @var $this \humhub\components\View */
/* @var $user \humhub\modules\user\models\User */

?>

<div class="vcardWrapper">
    <div class="vcardContent">
        <div class="vcardHeader"
             style="<?php if ($user->getProfileBannerImage()->hasImage()): ?> background-image: url(<?= $user->getProfileBannerImage()->getUrl(); ?>);<?php endif; ?>">
            <div class="headerContent">
                <div class="imageWrapper pull-left"><?= Image::widget(['user' => $user, 'width' => 60]); ?></div>
                <div class="displayName"><?= Html::encode($user->displayName); ?></div>
                <div class="title"><?= Html::encode($user->profile->title); ?></div>
            </div>
        </div>
        <div class="vcardBody">
            <?= $description ?>

            <?= VCardAddons::widget(['container' => $user]); ?>
        </div>
        <div class="vcardFooter">
            <?php if (Yii::$app->hasModule('mail') && !Yii::$app->user->isGuest && Yii::$app->user->id !== $user->id): ?>
                <?= Html::a(Yii::t('MailModule.base', 'Send message'), ['/mail/mail/create', 'ajax' => 1, 'userGuid' => $user->guid], ['class' => 'btn btn-primary btn-sm', 'data-target' => '#globalModal']); ?>
            <?php endif; ?>
            <div class="pull-right">
                <a href="<?= Url::to(['/', 'container' => $user]); ?>"
                   class="btn btn-primary btn-sm"><?= Yii::t('PopoverVcardModule.base', 'Open profile'); ?></a>
            </div>
        </div>
    </div>
</div>