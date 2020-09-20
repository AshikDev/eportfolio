<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\hubcalendar\assets;

use yii\web\AssetBundle;

class FullCalendarAssets extends AssetBundle
{
    public $defer = true;

    public $publishOptions = ['forceCopy' => false];
    
    public $sourcePath = '@hubcalendar/resources';

    public $css = ['css/fullcalendar.bundle.min.css'];

    public $js = ['js/fullcalendar.bundle.min.js'];
}
