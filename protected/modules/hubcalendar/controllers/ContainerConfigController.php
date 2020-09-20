<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 23.07.2017
 * Time: 23:00
 */

namespace humhub\modules\hubcalendar\controllers;


use humhub\modules\admin\permissions\ManageSpaces;
use humhub\modules\hubcalendar\permissions\ManageEntry;

class ContainerConfigController extends AbstractConfigController
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
          ['permission' => [ManageSpaces::class, ManageEntry::class]]
        ];
    }
}