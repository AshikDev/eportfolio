<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\linkpreview\assets;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    
    public $publishOptions  = ['forceCopy' => false];
    
    public $sourcePath = '@linkpreview/resources';

    public $css = [
        'css/linkpreview.css',
    ];

    public $js = [
        'js/humhub.linkpreview.js'
    ];
}
