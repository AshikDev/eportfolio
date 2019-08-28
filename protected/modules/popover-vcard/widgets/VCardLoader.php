<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard\widgets;
use humhub\components\Widget;
use humhub\modules\popovervcard\assets\Assets;
use yii\helpers\Url;


/**
 * Class VCardLoader
 * @package humhub\modules\popovervcard\widgets
 */
class VCardLoader extends Widget
{

    public function run() {

        Assets::register($this->view);

        $this->view->registerJsVar('vCardDelay', 500);
        $this->view->registerJsVar('vCardLoadUrl', Url::to(['/popover-vcard/index/load']));

        return;
    }

}