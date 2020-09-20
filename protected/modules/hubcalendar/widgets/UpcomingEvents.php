<?php

namespace humhub\modules\hubcalendar\widgets;

use Yii;
use humhub\components\Widget;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use humhub\modules\hubcalendar\models\CalendarEntryQuery;
use humhub\modules\hubcalendar\models\SnippetModuleSettings;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\hubcalendar\helpers\Url;

/**
 * UpcomingEvents shows next events in sidebar.
 *
 * @package humhub.modules_core.hubcalendar.widgets
 * @author luke
 */
class UpcomingEvents extends Widget
{

    /**
     * ContentContainer to limit events to. (Optional)
     *
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * How many days in future events should be shown?
     *
     * @var int
     */
    public $daysInFuture = 7;

    public function run()
    {
        $settings = SnippetModuleSettings::instantiate();
        /** @var CalendarService $calendarService */
        $calendarService = Yii::$app->getModule('hubcalendar')->get(CalendarService::class);

        $filters = [];

        if(!$this->contentContainer) {
            $filters[] = CalendarEntryQuery::FILTER_DASHBOARD;
        }

        $calendarEntries = $calendarService->getUpcomingEntries($this->contentContainer, $settings->upcomingEventsSnippetDuration, $settings->upcomingEventsSnippetMaxItems, $filters);

        if (empty($calendarEntries)) {
            return;
        }

        return $this->render('upcomingEvents', ['calendarEntries' => $calendarEntries, 'calendarUrl' => Url::toCalendar($this->contentContainer)]);
    }

}
