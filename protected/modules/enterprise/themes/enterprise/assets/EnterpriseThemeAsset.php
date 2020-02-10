<?php

namespace humhub\modules\enterprise\themes\enterprise\assets;

use yii\web\View;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EnterpriseThemeAsset
 *
 * @author buddha
 */
class EnterpriseThemeAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $jsOptions = ['position' => View::POS_END];
    
    /**
     * @inheritdoc
     */
    public $sourcePath = '@enterprise/themes/enterprise';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/humhub.enterprise.theme.js'
    ];
}
