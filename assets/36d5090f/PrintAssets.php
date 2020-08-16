<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\assets;

use yii\web\AssetBundle;

class PrintAssets extends AssetBundle
{

    public $sourcePath = '@meeting/resources';

    public $css = [
        'css/meeting_print.css',
    ];

    public $js = [
    ];
}
