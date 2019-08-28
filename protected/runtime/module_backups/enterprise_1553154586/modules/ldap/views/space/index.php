<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;
use humhub\modules\space\modules\manage\widgets\MemberMenu;
?>

<br />
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('EnterpriseModule.ldap', '<strong>LDAP</strong> member mapping'); ?>
    </div>
    <?= MemberMenu::widget(['space' => $space]); ?>
    <div class="panel-body">
        <p class="pull-right">
            <?= Html::a(Yii::t('EnterpriseModule.ldap', "Create new mapping"), $space->createUrl('edit'), ['class' => 'btn btn-success', 'data-ui-loader' => true]); ?>
        </p>
        <?= Yii::t('EnterpriseModule.ldap', 'Assign LDAP users which becomes automatically member of this space.'); ?><br>

        <br>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover'],
            'columns' => [
                [
                    'attribute' => 'dn',
                    'label' => 'Mapping'
                ],
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['width' => '80px'],
                    'buttons' => [
                        'update' => function($url, $model) use ($space) {
                            return Html::a('<i class="fa fa-pencil"></i>', $space->createUrl('edit', ['id' => $model->id]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                        'view' => function() {
                            return;
                        },
                        'delete' => function() {
                            return;
                        },
                    ],
                ],
        ]]);
        ?>        
        <br />
        <br />
        <small class="pull-right"><?= Yii::t('EnterpriseModule.ldap', 'Note: This option is only available for global user administrators.'); ?></small><br>

    </div>
</div>
