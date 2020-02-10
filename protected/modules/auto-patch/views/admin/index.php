<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;

/* @var $this \humhub\components\View */
/* @var $patchesInfo \humhub\modules\autopatch\components\PatchInfo[] */

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('AutoPatchModule.base', '<strong>HumHub</strong> Patches'); ?></div>
    <div class="panel-body">
        <?php if (Yii::$app->session->hasFlash('patchError')): ?>
            <div class="alert alert-danger">
                <strong><?= Yii::t('AutoPatchModule.base', 'Patch could not be applied!'); ?></strong><br/>
                <?= Yii::$app->session->getFlash('patchErrorMessage') ?>
            </div>
        <?php endif; ?>


        <?php if (count($patchesInfo) === 0): ?>

            <div class="alert alert-success">
                <?= Yii::t('AutoPatchModule.base', 'There are no patches available for your installed version!'); ?>
            </div>

        <?php else: ?>

            <p><?= Yii::t('AutoPatchModule.base', 'Below you can find a list of available patches for the currently installed HumHub version.'); ?></p>
            <p><?= Yii::t('AutoPatchModule.base', 'Patches offer a quick and easy way to fix serious bugs automatically without a complete software update.'); ?></p>
            <br/>
            <p><?= Yii::t('AutoPatchModule.base', 'It is highly recommended to install all available patches or update to the latest HumHub version as soon as possible.'); ?></p>
            <p><?= Yii::t('AutoPatchModule.base', 'Please make also sure, to use the latest available version of the AutoPatch module before proceeding.'); ?></p>
            <br/>

            <ul class="media-list">
                <?php foreach ($patchesInfo as $patchInfo): ?>
                    <li>
                        <div class="media">
                            <div class="media-body">

                                <?php if (!$patchInfo->isApplied()): ?>
                                    <?= Html::a(Yii::t('AutoPatchModule.base', 'Apply patch'), ['apply', 'id' => $patchInfo->id], ['class' => 'btn btn-success pull-right', 'data-ui-loader' => '', 'data-method' => 'POST']); ?>
                                <?php else: ?>
                                    <?= Html::a('<i class="fa fa-check"></i>&nbsp;&nbsp;' . Yii::t('AutoPatchModule.base', 'Patch applied'), null, ['class' => 'btn btn-success pull-right disabled']); ?>
                                <?php endif; ?>

                                <h4 class="media-heading"><?= $patchInfo->name; ?></h4>
                                <?= $patchInfo->description; ?>

                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>

    </div>
</div>
