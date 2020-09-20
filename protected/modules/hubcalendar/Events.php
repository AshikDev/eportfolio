<?php

namespace humhub\modules\hubcalendar;

use DateTime;
use humhub\modules\hubcalendar\models\CalendarEntryParticipant;
use Yii;
use humhub\modules\hubcalendar\interfaces\event\EditableEventIF;
use humhub\modules\hubcalendar\interfaces\event\CalendarItemTypesEvent;
use humhub\modules\hubcalendar\interfaces\recurrence\RecurrentEventIF;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\hubcalendar\helpers\CalendarUtils;
use humhub\modules\hubcalendar\interfaces\event\CalendarEventIF;
use humhub\modules\hubcalendar\integration\BirthdayCalendar;
use humhub\modules\hubcalendar\interfaces\reminder\CalendarEventReminderIF;
use humhub\modules\hubcalendar\models\reminder\ReminderService;
use humhub\modules\hubcalendar\models\reminder\CalendarReminder;
use humhub\modules\hubcalendar\models\reminder\CalendarReminderSent;
use humhub\modules\hubcalendar\models\SnippetModuleSettings;
use humhub\modules\hubcalendar\widgets\DownloadIcsLink;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use humhub\modules\hubcalendar\widgets\ReminderLink;
use humhub\modules\hubcalendar\widgets\UpcomingEvents;
use humhub\modules\content\models\Content;
use humhub\modules\hubcalendar\helpers\Url;
use yii\db\StaleObjectException;
use yii\helpers\Console;

/**
 * Description of CalendarEvents
 *
 * @author luke
 */
class Events
{
    /**
     * @inheritdoc
     */
    public static function onBeforeRequest()
    {
        try {
            static::registerAutoloader();
            Yii::$app->getModule('hubcalendar')->set(CalendarService::class, ['class' => CalendarService::class]);
        } catch (\Throwable $e) {
            Yii::error($e);
        }

    }

    /**
     * Register composer autoloader when Reader not found
     */
    public static function registerAutoloader()
    {
        require Yii::getAlias('@hubcalendar/vendor/autoload.php');
    }

    /**
     * @param $event CalendarItemTypesEvent
     * @return mixed
     */
    public static function onGetCalendarItemTypes($event)
    {
        try {
            BirthdayCalendar::addItemTypes($event);
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    /**
     * @param $event \humhub\modules\hubcalendar\interfaces\event\CalendarItemsEvent;
     * @throws \Throwable
     */
    public static function onFindCalendarItems($event)
    {
        try {
            BirthdayCalendar::addItems($event);
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onTopMenuInit($event)
    {
        try {
            if (SnippetModuleSettings::instantiate()->showGlobalCalendarItems()) {
                $event->sender->addItem([
                    'label' => Yii::t('CalendarModule.base', 'Hub Calendar'),
                    'url' => Url::toGlobalCalendar(),
                    'icon' => '<i class="fa fa-calendar"></i>',
                    'isActive' => (Yii::$app->controller->module
                        && Yii::$app->controller->module->id == 'hubcalendar'
                        && Yii::$app->controller->id == 'global'),
                    'sortOrder' => 300,
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onSpaceMenuInit($event)
    {
        try {
            $space = $event->sender->space;
            if ($space->isModuleEnabled('hubcalendar')) {
                $event->sender->addItem([
                    'label' => Yii::t('CalendarModule.base', 'Hub Calendar'),
                    'group' => 'modules',
                    'url' => Url::toCalendar($space),
                    'icon' => '<i class="fa fa-calendar"></i>',
                    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'hubcalendar'),

                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onProfileMenuInit($event)
    {
        try {
            $user = $event->sender->user;
            if ($user->isModuleEnabled('hubcalendar')) {
                $event->sender->addItem([
                    'label' => Yii::t('CalendarModule.base', 'Hub Calendar'),
                    'url' => Url::toCalendar($user),
                    'icon' => '<i class="fa fa-calendar"></i>',
                    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'hubcalendar'),
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        try {
            $space = $event->sender->space;
            $settings = SnippetModuleSettings::instantiate();

            if ($space->isModuleEnabled('hubcalendar')) {
                if ($settings->showUpcomingEventsSnippet()) {
                    $event->sender->addWidget(UpcomingEvents::class, ['contentContainer' => $space], ['sortOrder' => $settings->upcomingEventsSnippetSortOrder]);
                }
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onDashboardSidebarInit($event)
    {
        try {
            $settings = SnippetModuleSettings::instantiate();

            if ($settings->showUpcomingEventsSnippet()) {
                $event->sender->addWidget(UpcomingEvents::class, [], ['sortOrder' => $settings->upcomingEventsSnippetSortOrder]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onProfileSidebarInit($event)
    {
        try {
            if (Yii::$app->user->isGuest) {
                return;
            }

            $user = $event->sender->user;
            if ($user != null) {
                $settings = SnippetModuleSettings::instantiate();

                if ($settings->showUpcomingEventsSnippet()) {
                    $event->sender->addWidget(UpcomingEvents::class, ['contentContainer' => $user], ['sortOrder' => $settings->upcomingEventsSnippetSortOrder]);
                }
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onWallEntryLinks($event)
    {
        try {
            $eventModel = static::getCalendarEvent($event->sender->object);

            if(!$eventModel) {
                return;
            }

            if ($eventModel instanceof ContentActiveRecord && $eventModel instanceof CalendarEventIF) {
                $event->sender->addWidget(DownloadIcsLink::class, ['calendarEntry' => $eventModel]);
            }

            /* @var $eventModel CalendarEventIF */
            if($eventModel->getStartDateTime() <= new DateTime()) {
                return;
            }

            if($eventModel instanceof CalendarEventReminderIF) {
                $event->sender->addWidget(ReminderLink::class, ['entry' => $eventModel]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    /**
     * @param $model
     * @return CalendarEventIF|null
     */
    private static function getCalendarEvent($model)
    {
        if($model instanceof CalendarEventIF) {
            return $model;
        }

        if(method_exists($model, 'getCalendarEvent')) {
            $event = $model->getCalendarEvent();
            if($event instanceof CalendarEventIF) {
                return $event;
            }
        }

        return null;
    }

    public static function onRecordBeforeInsert($event)
    {
        try {
            static::onRecordBeforeUpdate($event);
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onRecordBeforeUpdate($event)
    {
        try {
            $model = CalendarUtils::getCalendarEvent($event->sender);
            if($model && ($model instanceof EditableEventIF)) {
                /** @var $model EditableEventIF **/
                if(empty($model->getUid())) {
                    $model->setUid(CalendarUtils::generateEventUid($model));
                }
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    /**
     * @param $event
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function onRecordBeforeDelete($event)
    {
        try {
            $model = CalendarUtils::getCalendarEvent($event->sender);

            if(!$model || !($model instanceof CalendarEventReminderIF)) {
                return;
            }

            foreach(CalendarReminder::getEntryLevelReminder($model) as $reminder) {
                $reminder->delete();
            }

            if($model instanceof RecurrentEventIF) {
                $model->getRecurrenceQuery()->onDelete();
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }

    }

    /**
     * @param $event
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("Calendar Module (" . CalendarReminder::find()->count() . " reminder entries)");

        foreach (CalendarReminder::find()->all() as $reminder) {
            if ($reminder->isEntryLevelReminder() && !Content::findOne(['id' => $reminder->content_id])) {
                if ($integrityController->showFix("Delete calendar reminder " . $reminder->id . " without existing entry relation!")) {
                    $reminder->delete();
                }
            }
        }

        $integrityController->showTestHeadline("Calendar Module (" . CalendarReminderSent::find()->count() . " reminder sent entries)");

        foreach (CalendarReminderSent::find()->all() as $reminderSent) {
            if(!Content::findOne(['id' => $reminderSent->content_id])) {
                if ($integrityController->showFix("Delete hubcalendar reminder sent" . $reminderSent->id . " without existing entry relation!")) {
                    $reminderSent->delete();
                }
            }
        }
    }

    /**
     * Callback when a user is completely deleted.
     *
     * @param \yii\base\Event $event
     */
    public static function onUserDelete($event)
    {
        $user = $event->sender;
        foreach (CalendarEntryParticipant::findAll(['user_id' => $user->id]) as $participant) {
            $participant->delete();
        }
    }

    public static function onCronRun($event)
    {
        static::onBeforeRequest();

        /* @var $module Module */
        $module = Yii::$app->getModule('hubcalendar');
        $lastRunTS = $module->settings->get('lastReminderRunTS');

        if(!$lastRunTS || ((time() - $lastRunTS) >= $module->getRemidnerProcessIntervalS())) {
            try {
                $controller = $event->sender;
                $controller->stdout("Running reminder process... ");
                (new ReminderService())->sendAllReminder();
                $controller->stdout('done.' . PHP_EOL, Console::FG_GREEN);
            } catch (\Throwable $e) {
                Yii::error($e);
                $controller->stdout('error.' . PHP_EOL, Console::FG_RED);
                $controller->stderr("\n".$e->getTraceAsString()."\n", Console::BOLD);
            }
            $module->settings->set('lastReminderRunTS', time());
        }
    }
}
