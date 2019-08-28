<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;
?>

<?php $this->beginContent('@admin/views/group/_manageLayout.php', ['group' => $group]) ?>
<div class="panel-body">
    <p />        
    <p class="pull-right">
        <?= Html::a(Yii::t('EnterpriseModule.ldap', "Create new mapping"), Url::to(['edit', 'groupId' => $group->id]), ['class' => 'btn btn-success', 'data-ui-loader' => '']); ?>
    </p>
    <div class="help-block">
        <?= Yii::t('EnterpriseModule.ldap', 'Assign LDAP users which becomes automatically member of this group.'); ?><br>
    </div>
    <br>
    <?php
    echo GridView::widget([
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
                    'update' => function($url, $model) {
                        return Html::a('<i class="fa fa-pencil"></i>', Url::to(['edit', 'id' => $model->id, 'groupId' => $model->group_id]), ['class' => 'btn btn-primary btn-xs tt']);
                    },
                    'view' => function() {
                        return;
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fa fa-remove"></i>', Url::to(['delete', 'id' => $model->id, 'groupId' => $model->group_id]), ['class' => 'btn btn-danger btn-xs tt']);
                    },
                ],
            ],
    ]]);
    ?>        
</div>
<?php $this->endContent(); ?>