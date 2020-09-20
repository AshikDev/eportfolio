<?php

namespace humhub\modules\hubcalendar\helpers;

use humhub\modules\hubcalendar\interfaces\reminder\CalendarEventReminderIF;
use humhub\modules\hubcalendar\interfaces\event\CalendarTypeIF;
use humhub\modules\hubcalendar\models\CalendarEntry;
use humhub\modules\hubcalendar\models\CalendarEntryParticipant;
use humhub\modules\hubcalendar\models\CalendarEntryType;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Url as BaseUrl;

class Url extends BaseUrl
{
    public static function toConfig(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config');
        }

        return  BaseUrl::to(['/hubcalendar/config']);
    }

    public static function toConfigTypes(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/types');
        }

        return static::to(['/hubcalendar/config/types']);
    }

    public static function toEditType(CalendarEntryType $model, ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/edit-type',  ['id' => $model->id] );
        }

        return static::to(['/hubcalendar/config/edit-type', 'id' => $model->id]) ;
    }

    public static function toCreateType(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/edit-type' );
        }

        return static::to(['/hubcalendar/config/edit-type']) ;
    }

    public static function toDeleteType(CalendarEntryType $model, ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/delete-type', ['id' => $model->id]);
        }

        return static::to(['/hubcalendar/config/delete-type', 'id' => $model->id]);
    }

    public static function toConfigCalendars(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/calendars');
        }

        return static::to(['/hubcalendar/config/calendars']);
    }

    public static function toConfigSnippets()
    {
        return static::toRoute(['/hubcalendar/config/snippet']);
    }

    public static function toCalendar(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/view/index');
        }

        return static::toGlobalCalendar();
    }

    public static function toGlobalCalendar()
    {
        return static::to(['/hubcalendar/global/index']);
    }

    public static function toEditItemType(CalendarTypeIF $type, ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/edit-calendars', ['key' => $type->getKey()]);
        }

        return static::to(['/hubcalendar/config/edit-calendars', 'key' => $type->getKey()]);
    }

    public static function toParticipationSettingsReset(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/container-config/reset-participation-config');
        }

        return static::to(['/hubcalendar/config/reset-participation-config']);
    }

    public static function toAjaxLoad(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/hubcalendar/view/load-ajax');
        }

        return static::to(['/hubcalendar/global/load-ajax']);
    }

    public static function toGlobalCreate()
    {
        return static::to(['/hubcalendar/global/select']);
    }

    public static function toEditEntry(CalendarEntry $entry, $cal = null, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        return $container->createUrl('/hubcalendar/entry/edit', ['id' => $entry->id, 'cal' => $cal]);
    }

    public static function toFullCalendarEdit(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/hubcalendar/entry/edit', ['cal' => 1]);
    }

    public static function toCreateEntry(ContentContainerActiveRecord $container, $start = null, $end = null)
    {
        return $container->createUrl('/hubcalendar/entry/edit', ['start' => $start, 'end' => $end]);
    }

    public static function toEnableProfileModule(User $user)
    {
        return $user->createUrl('/user/account/enable-module', ['moduleId' => 'hubcalendar']);
    }

    public static function toEntry(CalendarEntry $entry, $cal = 0, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        $params =  ['id' => $entry->id];
        if($cal) {
            $params['cal'] = 1;
        }

        if(RecurrenceHelper::isRecurrentInstance($entry)) {
            $params['parent_id'] = $entry->parent_event_id;
            $params['recurrence_id'] = $entry->recurrence_id;
            return $container->createUrl('/hubcalendar/entry/view-recurrence', $params);
        }

        // Container should always be present but, in order to prevent null pointer (https://community.humhub.com/s/general-discussion/?contentId=209345)
        return $container ? $container->createUrl('/hubcalendar/entry/view', $params) : '';
    }

    public static function toEntryDelete(CalendarEntry $entry, $cal = 0, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        $params =  ['id' => $entry->id];
        if($cal) {
            $params['cal'] = 1;
        }

        return $container->createUrl('/hubcalendar/entry/delete', $params);
    }

    public static function toEntryToggleClose(CalendarEntry $entry, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        return $container->createUrl('/hubcalendar/entry/toggle-close', ['id' => $entry->id]);
    }

    public static function toEntryDownloadICS(ContentActiveRecord $entry, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        return $container->createUrl('/hubcalendar/ical/export', ['id' => $entry->content->id]);
    }

    public static function toUserLevelReminderConfig(CalendarEventReminderIF $entry, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->getContentRecord()->container;
        }

        return $container->createUrl('/hubcalendar/reminder/set', ['id' => $entry->getContentRecord()->id]);
    }

    public static function toEntryRespond(CalendarEntry $entry, $state, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        $participantSate = $entry->participation->getParticipationStatus(Yii::$app->user->getidentity());

        return $container->createUrl('/hubcalendar/entry/respond', [
            'type' => $participantSate == $state ? CalendarEntryParticipant::PARTICIPATION_STATE_NONE : $state,
            'id' => $entry->id]);
    }

    public static function toParticipationUserList(CalendarEntry $entry, $state, ContentContainerActiveRecord $container = null)
    {
        if(!$container) {
            $container = $entry->content->container;
        }

        return $container->createUrl('/hubcalendar/entry/user-list', ['id' => $entry->id, 'state' => $state]);
    }

    public static function toEnableModuleOnProfileConfig()
    {
        if(Yii::$app->user->isGuest) {
            return null;
        }

        return Yii::$app->user->identity->createUrl('/hubcalendar/global/enable-config');
    }

    public static function toUpdateEntry(ContentActiveRecord $entry)
    {
        return static::to(['/hubcalendar/full-calendar/update', 'id' => $entry->content->id]);
    }
}