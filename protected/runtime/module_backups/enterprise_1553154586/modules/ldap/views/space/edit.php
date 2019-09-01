<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use humhub\modules\space\modules\manage\widgets\MemberMenu;
?>

<br />
<div class="panel panel-default">
    <?= MemberMenu::widget(['space' => $space]); ?>
    <div class="panel-heading">
        <?php if (!$model->isNewRecord) : ?>
            <?= Yii::t('EnterpriseModule.ldap', '<strong>Edit</strong> ldap mapping'); ?>
        <?php else: ?>
            <?= Yii::t('EnterpriseModule.ldap', '<strong>Create</strong> new ldap mapping'); ?>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <p class="pull-right">
            <?= Html::a(Yii::t('EnterpriseModule.ldap', "Back to overview"), $space->createUrl('index'), ['class' => 'btn btn-default', 'data-ui-loader' => true]); ?>
        </p>
        <?= $this->render('@enterprise/modules/ldap/views/_mappinghelp.php'); ?>
        <br />
        <?php $form = ActiveForm::begin([]) ?>
        <?= $form->field($model, 'dn') ?>

        <br />
        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-success', 'data-ui-loader' => true]) ?>

        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(Yii::t('base', 'Delete'), $space->createUrl('delete', ['id' => $model->id]), array('class' => 'btn btn-danger pull-right', 'data-ui-loader' => true)); ?>
        <?php endif; ?>

        <?php ActiveForm::end() ?>

    </div>
</div>