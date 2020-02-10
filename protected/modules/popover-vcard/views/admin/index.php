<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('PopoverVcardModule.base', '<strong>VCard</strong> Configuration'); ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id' => 'configure-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

        <div class="row">
            <div class="col-md-6">
                <h4><?= Yii::t('PopoverVcardModule.base', 'User VCard'); ?></h4>
                <br/>
                <?= $form->field($model, 'userEnabled')->checkbox(); ?>
                <?= $form->field($model, 'userContent')->textarea(['rows' => 15]); ?>


                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <colgroup>
                            <col class="col-xs-5">
                            <col class="col-xs-7">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>&nbsp;&nbsp;<?= Yii::t('PopoverVcardModule.base', 'Variable name'); ?></th>
                            <th>&nbsp;&nbsp;Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><code>{{ user.email }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The email of this user.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ user.FIELDNAME }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'You can use any field from the user model.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ profile.firstname }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The first name of the user.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ profile.lastname }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The last name of the user.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ profile.FIELDNAME }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'Each available profile field.'); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <h4><?= Yii::t('PopoverVcardModule.base', 'Space VCard'); ?></h4>
                <br/>
                <?= $form->field($model, 'spaceEnabled')->checkbox(); ?>
                <?= $form->field($model, 'spaceContent')->textarea(['rows' => 15]); ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <colgroup>
                            <col class="col-xs-5">
                            <col class="col-xs-7">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>&nbsp;&nbsp;<?= Yii::t('PopoverVcardModule.base', 'Variable name'); ?></th>
                            <th>&nbsp;&nbsp;<?= Yii::t('PopoverVcardModule.base', 'Description'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><code>{{ space.name }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The name of this space.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ space.description }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The description of this space.'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><code>{{ memberCount }}</code></th>
                            <td><?= Yii::t('PopoverVcardModule.base', 'The current member count.'); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <br/>
        <br/>
        <p>
            <?= Yii::t('PopoverVcardModule.base', 'See more information about the template language:'); ?>
            <a href="https://twig.symfony.com/doc/2.x/templates.html" target="_blank">
                <i class="fa fa-arrow-right"></i> <?= Yii::t('PopoverVcardModule.base', 'Twig for Template Designers'); ?>
            </a>
        </p>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>