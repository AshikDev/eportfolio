<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use humhub\modules\space\widgets\SpaceNameColorInput;

/* @var $model \humhub\modules\space\models\Space */
/* @var $visibilityOptions array */
/* @var $joinPolicyOptions array */

?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">
                <?= Yii::t('EnterpriseModule.spacetype', '<strong>Create</strong> new %typeTitle%', ['%typeTitle%' => $this->context->getTypeTitle($model)]); ?>
            </h4>
        </div>
        <div class="modal-body">
            <?= SpaceNameColorInput::widget(['form' => $form, 'model' => $model]) ?>

            <?= $form->field($model, 'description')->textarea(['placeholder' => Yii::t('SpaceModule.views_create_create', 'Description'), 'rows' => '3']); ?>

            <a data-toggle="collapse" id="access-settings-link" href="#collapse-access-settings" style="font-size: 11px;">
                <i class="fa fa-caret-right"></i> <?= Yii::t('SpaceModule.views_create_create', 'Advanced access settings'); ?>
            </a>

            <div id="collapse-access-settings" class="panel-collapse collapse">
                <br/>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'visibility')->radioList($visibilityOptions)->hint(false); ?>
                    </div>
                    <div class="col-md-6 spaceJoinPolicy">
                        <?= $form->field($model, 'join_policy')->radioList($joinPolicyOptions)->hint(false); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class=" modal-footer">
            <a href="#" class="btn btn-primary" 
               data-action-click="ui.modal.submit" 
               data-ui-loader
               data-action-url="<?= Url::to(['/enterprise/spacetype/create-space/create', 'type_id' => $model->space_type_id]) ?>">
                   <?= Yii::t('SpaceModule.views_create_create', 'Next'); ?>
            </a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>


<script type="text/javascript">
    var $checkedVisibility = $('input[type=radio][name="Space[visibility]"]:checked');
    if($checkedVisibility.length && $checkedVisibility[0].value == 0) {
        $('.spaceJoinPolicy').hide();
    }

    $('input[type=radio][name="Space[visibility]"]').on('change', function() {
        if(this.value == 0) {
            $('.spaceJoinPolicy').fadeOut();
        } else {
            $('.spaceJoinPolicy').fadeIn();
        }
    });

    $('#collapse-access-settings').on('show.bs.collapse', function () {
        // change link arrow
        $('#access-settings-link i').removeClass('fa-caret-right');
        $('#access-settings-link i').addClass('fa-caret-down');
    });

    $('#collapse-access-settings').on('hide.bs.collapse', function () {
        // change link arrow
        $('#access-settings-link i').removeClass('fa-caret-down');
        $('#access-settings-link i').addClass('fa-caret-right');
    });
</script>