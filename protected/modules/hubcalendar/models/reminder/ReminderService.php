<?php


namespace humhub\modules\hubcalendar\models\reminder;



use Yii;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use yii\base\Component;

class ReminderService extends Component
{
    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function sendAllReminder() {
        $calendarService = Yii::$app->getModule('hubcalendar')->get(CalendarService::class);
        (new ReminderProcessor(['calendarService' => $calendarService]))->run();
    }
}