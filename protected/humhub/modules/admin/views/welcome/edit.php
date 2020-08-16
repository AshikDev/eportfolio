<?php

use humhub\libs\Html;
use yii\bootstrap\ActiveForm;
use humhub\widgets\MarkdownEditor;

humhub\assets\TabbedFormAsset::register($this);

/* @var $hForm \humhub\compat\HForm */
/* @var $user \humhub\modules\user\models\User */
?>

<div class="clearfix">
    <div class="panel-body">
        <?= Html::backButton(['display'], ['label' => Yii::t('AdminModule.base', 'Back to overview'), 'class' => 'pull-right']); ?>
    </div>
</div>
<div class="panel-body">
    <?php $form = ActiveForm::begin(['id' => 'welcome-edit-form']); ?>
    <?= $form->field($model, 'content')->textarea(['id' => 'markdownField', 'class' => 'form-control', 'rows' => '15']);; ?>
    <?= MarkdownEditor::widget(['fieldId' => 'markdownField']); ?>
    <?= Html::submitButton( 'Save', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
</div>
