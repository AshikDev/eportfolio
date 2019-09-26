<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo Yii::t('FreichatModule.base', 'FreiChat configuration'); ?></div>

    <div class="panel-body">

        <?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
        <div class="form-group">
            <?php echo $form->field($model, 'token'); ?>
        </div>

        <div class="form-group">
            <?php echo Html::submitButton(Yii::t('FreichatModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
            <a class="btn btn-default" href="<?php echo Url::to(['/admin/module']); ?>"><?php echo Yii::t('FreichatModule.base', 'Back to modules'); ?></a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
