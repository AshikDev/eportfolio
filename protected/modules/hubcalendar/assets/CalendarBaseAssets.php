<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\hubcalendar\assets;

use yii\web\AssetBundle;

class CalendarBaseAssets extends AssetBundle
{
    public $defer = true;

    public $publishOptions = [
        'forceCopy' => false
    ];
    
    public $sourcePath = '@hubcalendar/resources';

    public $css = [
        'css/calendar.css',
    ];
    public $js = [
        'js/humhub.calendar.js'
    ];
}
