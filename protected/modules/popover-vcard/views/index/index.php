<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\popovervcard\widgets\VCardSpace;
use humhub\modules\popovervcard\widgets\VCardUser;


/* @var $this \humhub\components\View */

$user = \humhub\modules\user\models\User::findOne(['id' => 1]);
$space = \humhub\modules\space\models\Space::findOne(['id' => 1]);
?>


<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= \humhub\libs\Html::containerLink($user); ?>&nsbp;
            <?= \humhub\libs\Html::containerLink($space); ?><br />

            <br/><br/><br/><br/><br/>

            <hr />

            <?= VCardUser::widget(['user' => $user]); ?>
            <?= VCardSpace::widget(['space' => $space]); ?>

        </div>
    </div>
</div>
