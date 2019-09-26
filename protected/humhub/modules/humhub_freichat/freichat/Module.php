<?php

namespace humhub\modules\freichat;

use yii\helpers\Url;

class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/freichat/config/config']);
    }

    public function getToken()
    {
        $token = $this->settings->get('token');
        if (empty($token)) {
            return '';
        }
        return $token;
    }

}