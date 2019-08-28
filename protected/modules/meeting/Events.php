<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting;

use humhub\modules\meeting\integration\calendar\MeetingCalendar;
use humhub\modules\meeting\models\MeetingItem;
use humhub\modules\meeting\models\MeetingTask;
use humhub\modules\meeting\widgets\TaskAddon;
use Yii;


/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 14.09.2017
 * Time: 12:12
 */
class Events
{
    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     * @return mixed
     */
    public static function onGetCalendarItemTypes($event)
    {
        $contentContainer = $event->contentContainer;

        if(!$contentContainer || $contentContainer->isModuleEnabled('meeting')) {
            MeetingCalendar::addItemTypes($event);
        }
    }

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemsEvent;
     */
    public static function onFindCalendarItems($event)
    {
        $contentContainer = $event->contentContainer;

        if(!$contentContainer || $contentContainer->isModuleEnabled('meeting')) {
            MeetingCalendar::addItems($event);
        }
    }

    /**
     * Callback to validate module database records.
     *
     * @param Event $event
     */
    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("Meeting Module (" . MeetingTask::find()->count() . " task relations)");

        foreach (MeetingTask::find()->all() as $meetingTask) {
            if (!$meetingTask->task) {
                if ($integrityController->showFix("Delete meeting_task " . $meetingTask->id . " without existing task relation!")) {
                    $meetingTask->delete();
                }
            }
        }
    }


    public static function onSpaceMenuInit($event)
    {
        /* @var $space \humhub\modules\space\models\Space */

        $space = $event->sender->space;

        if ($space->isModuleEnabled('meeting') && $space->isMember()) {

            $event->sender->addItem([
                'label' => Yii::t('MeetingModule.base', 'Meetings'),
                'group' => 'modules',
                'url' => $space->createUrl('//meeting/index'),
                'icon' => '<i class="fa fa-calendar-o"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'meeting'),
            ]);
        }
    }

    public static function onTaskDelete($event)
    {
        foreach (MeetingTask::find()->where(['task_id' => $event->sender->id])->all() as $meetingTask) {
            $meetingTask->delete();
        }
    }

    public static function onTaskWallEntry($event)
    {
        if (get_class($event->sender->object) == 'humhub\modules\tasks\models\Task') {
            $event->sender->addWidget(TaskAddon::className(), ['task' => $event->sender->object], ['sortOrder' => 2]);
        }
    }

}