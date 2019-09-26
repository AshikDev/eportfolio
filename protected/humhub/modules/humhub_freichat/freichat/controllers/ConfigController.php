<?php

namespace humhub\modules\freichat\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\freichat\models\ConfigureForm;

class ConfigController extends Controller
{

    public function actionConfig()
    {
        $model = new ConfigureForm();
        $model->token = Yii::$app->getModule('freichat')->settings->get('token');

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getModule('freichat')->settings->set('token', $model->token);
            $this->view->saved();
        }

        return $this->render('config', ['model' => $model]);
    }

}
