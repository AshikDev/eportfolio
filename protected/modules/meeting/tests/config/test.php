<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

return [
    'humhub_root' => 'E:\codebase\humhub\master',
    'modules' => ['meeting'],
    'fixtures' => [
        'default',
        'calendar_entry' => 'humhub\modules\meeting\tests\codeception\fixtures\MeetingFixture'
    ]
];



