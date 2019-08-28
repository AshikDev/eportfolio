<?php

namespace humhub\modules\user\authclient;

use yii\authclient\OAuth2;
use yii\web\HttpException;
use Yii;

class Orcid extends \yii\authclient\clients\Orcid
{


    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'cssIcon' => 'fa fa-github',
            'buttonBackgroundColor' => '#4078C0',
        ];
    }

    
    /**
     * {@inheritdoc}
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'username' => 'username',
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'email' => 'email',
            
        ];
    }

}
