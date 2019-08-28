<?php

use humhub\modules\content\widgets\richtext\RichText;
use yii\helpers\Html;
use humhub\modules\user\models\fieldtype\MarkdownEditor;
use humhub\widgets\MarkdownView;

/**
 * @var $this \humhub\components\View
 * @var $user \humhub\modules\user\models\User
 */
$categories = $user->profile->getProfileFieldCategories();
?>
<div class="panel panel-default">
    <div
        class="panel-heading"><?= Yii::t('UserModule.views_profile_about', '<strong>Orcid Login</strong>'); ?></div>
    <div class="panel-body">
        
        <?php
                    $guid = Yii::$app->request->get('cguid', Yii::$app->request->get('sguid', Yii::$app->request->get('uguid')));
                    $session = Yii::$app->session;
                    $session->set('cguid', $guid);

                    echo Html::a('Orcid', array('//user/orcid/orcid_link', 'cguid' => $guid), ['class' => 'btn btn-primary']);
                    ?>

    </div>
    <br>
</div>
