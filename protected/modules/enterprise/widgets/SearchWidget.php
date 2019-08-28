<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\widgets;

use Yii;
use humhub\components\Widget;

/**
 * SearchWidget display the search in the enterprise theme
 *
 * @author Luke
 */
class SearchWidget extends Widget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->user->isGuest && !\humhub\modules\user\components\User::isGuestAccessEnabled()) {
            return;
        }

        return $this->render('search');
    }

}
