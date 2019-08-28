<?php

namespace humhub\modules\termsbox\controllers;

use Yii;
use humhub\components\Controller;

class IndexController extends Controller
{

    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            ['login']
        ];
    }

    public function actionAccept()
    {
        $user = Yii::$app->user->getIdentity();
        $user->termsbox_accepted = true;
        $user->save();
        
        return $this->asJson(['success' => true]);
    }

    public function actionDecline()
    {
        $user = Yii::$app->user->getIdentity();
        $user->termsbox_accepted = false;
        $user->save();

        return $this->redirect(['/user/auth/logout']);
    }

}

?>
