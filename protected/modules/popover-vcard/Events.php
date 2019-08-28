<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard;

use humhub\modules\popovervcard\widgets\VCardLoader;
use humhub\widgets\LayoutAddons;


/**
 * @author luke
 */
class Events
{

    public static function onLayoutAddonsInit($event)
    {

        /** @var LayoutAddons $layoutAddons */
        $layoutAddons = $event->sender;

        $layoutAddons->addWidget(VCardLoader::class);

    }

}
