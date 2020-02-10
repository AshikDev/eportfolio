<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

return [
    'id' => 'auto-patch',
    'class' => 'humhub\modules\autopatch\Module',
    'namespace' => 'humhub\modules\autopatch',
    'events' => [
        ['class' => 'humhub\modules\admin\widgets\AdminMenu', 'event' => 'init', 'callback' => ['humhub\modules\autopatch\Events', 'onAdminMenuInit']],
    ]
];

?>