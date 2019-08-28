

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
        class="panel-heading"><?= Yii::t('UserModule.views_profile_about', '<strong>Orcid Details</strong> this user'); ?></div>
    <div class="panel-body">


        <?php
        $guid = Yii::$app->request->get('cguid', Yii::$app->request->get('sguid', Yii::$app->request->get('uguid')));
        $session = Yii::$app->session;
        $session->set('cguid', $guid);

//                    echo Html::a('Orcid', array('//user/orcid/orcid_link', 'cguid' => $guid), ['class' => 'btn btn-primary']);
        ?>     

        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading " data-toggle="collapse" data-parent="#accordion" href="#collapse1" >
                    <h4 class="panel-title ">
                        <a  data-toggle="collapse" data-parent="#accordion" href="#collapse1" >Education</a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse ">
                    <div class="panel-body">
                        <?php
                        echo '<br>';

                        $count = 0;
                        if (isset($attributes_education)) {
                            foreach ($attributes_education as $attributes_values) {

                                if (isset($attributes_values['COMMON:NAME'])) {
                                    echo " COMMON:NAME : " . $attributes_values['COMMON:NAME'] . '<br/>';
                                }
                                if (isset($attributes_values['COMMON:CITY'])) {
                                    echo "COMMON:CITY : " . $attributes_values['COMMON:CITY'] . '<br/>';
                                }
                                if (isset($attributes_values['EDUCATION:ROLE-TITLE']) && $attributes_values['EDUCATION:ROLE-TITLE'] != NULL && $attributes_values['EDUCATION:ROLE-TITLE'] != " ") {
                                    echo "EDUCATION:ROLE-TITLE - " . $attributes_values['EDUCATION:ROLE-TITLE'] . '<br/>';
                                }
                                echo '<br>';
                            }
                        }
                        else
                        {
                            echo "No data found". '<br/>';
                        }
                        ?>



                    </div>
                </div>
            </div>
            <div class="panel panel-default" >
                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Research Paper</a>
                    </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">Research Paper Research Paper Research Paper Research Paper  </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse3" >
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Work</a>
                    </h4>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php
                        echo '<br>';

                        $count = 0;
                        if (isset($attributes_works)) {
                            foreach ($attributes_works as $attributes_values) {
                                $count++;
                                if (isset($attributes_values['COMMON:TITLE'])) {
                                    echo $count . ". COMMON:TITLE: " . $attributes_values['COMMON:TITLE'] . '<br/>';
                                }
                                if (isset($attributes_values['WORK:TYPE'])) {
                                    echo "  WORK:TYPE  - " . $attributes_values['WORK:TYPE'] . '<br/>';
                                }
                                if (isset($attributes_values['COMMON:EXTERNAL-ID-VALUE'])) {
                                    echo "  COMMON:EXTERNAL-ID-VALUE - " . $attributes_values['COMMON:EXTERNAL-ID-VALUE'] . '<br/>';
                                }
                                echo '<br>';
                            }
                        }
                        else
                        {
                            echo "No data found". '<br/>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div> 


    </div>



</div>
