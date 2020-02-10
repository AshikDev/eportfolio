<?php /** @noinspection MissedFieldInspection */

return [
    'id' => 'popover-vcard',
    'class' => 'humhub\modules\popovervcard\Module',
    'namespace' => 'humhub\modules\popovervcard',
    'events' => [
        [\humhub\widgets\LayoutAddons::class, \humhub\widgets\LayoutAddons::EVENT_INIT, [\humhub\modules\popovervcard\Events::class, 'onLayoutAddonsInit']]
    ]
];
?>