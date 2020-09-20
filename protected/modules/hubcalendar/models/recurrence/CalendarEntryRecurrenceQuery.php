<?php


namespace humhub\modules\hubcalendar\models\recurrence;


use humhub\modules\hubcalendar\interfaces\recurrence\AbstractRecurrenceQuery;
use humhub\modules\hubcalendar\models\CalendarEntry;

class CalendarEntryRecurrenceQuery extends AbstractRecurrenceQuery
{
    public static $recordClass = CalendarEntry::class;
}