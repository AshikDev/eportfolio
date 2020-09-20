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
 * Date: 17.07.2017
 * Time: 21:02
 */

namespace humhub\modules\hubcalendar\widgets;


use humhub\modules\hubcalendar\helpers\Url;
use Yii;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use humhub\widgets\SettingsTabs;

class GlobalConfigMenu extends SettingsTabs
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        /* @var $calendarService CalendarService */
        $calendarService =  Yii::$app->getModule('hubcalendar')->get(CalendarService::class);

        $this->items = [
            [
                'label' => Yii::t('CalendarModule.widgets_GlobalConfigMenu', 'Defaults'),
                'url' => Url::toConfig(),
                'active' => $this->isCurrentRoute('hubcalendar', 'config', 'index'),
                'sortOrder' => 10
            ],
            [
                'label' => Yii::t('CalendarModule.widgets_GlobalConfigMenu', 'Event Types'),
                'url' =>  Url::toConfigTypes(),
                'active' => $this->isCurrentRoute('hubcalendar', 'config', 'types'),
                'sortOrder' => 20
            ],
            [
                'label' => Yii::t('CalendarModule.widgets_GlobalConfigMenu', 'Snippet'),
                'url' =>  Url::toConfigSnippets(),
                'active' => $this->isCurrentRoute('hubcalendar', 'config', 'snippet'),
                'sortOrder' => 30
            ],
        ];

        if(!empty($calendarService->getCalendarItemTypes())) {
            $this->items[] = [
                'label' => Yii::t('CalendarModule.widgets_GlobalConfigMenu', 'Calendars'),
                'url' => Url::toConfigCalendars(),
                'active' => $this->isCurrentRoute('hubcalendar', 'config', 'calendars'),
                'sortOrder' => 25
            ];
        }

        parent::init();
    }

}