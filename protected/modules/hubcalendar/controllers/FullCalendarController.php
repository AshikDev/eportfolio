<?php

namespace humhub\modules\hubcalendar\controllers;

use DateTime;
use humhub\components\access\StrictAccess;
use Yii;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\hubcalendar\helpers\CalendarUtils;
use humhub\modules\hubcalendar\interfaces\fullcalendar\FullCalendarEventIF;
use humhub\modules\content\models\Content;

class FullCalendarController extends Controller
{
    /**
     * @inheritdocs
     */
    public $access = StrictAccess::class;

    /**
     * @inheritdocs
     */
    public function getAccessRules()
    {
        return [
            ['login'],
            ['json'],
            ['post']
        ];
    }
    
    public function actionUpdate($id)
    {
        $this->forcePostRequest();

        $content = Content::findOne(['id' => $id]);

        if(!$content) {
            throw new HttpException(404);
        }

        if(!$content->canEdit()) {
            throw new HttpException(403);
        }

        $model = $content->getModel();


        $event = CalendarUtils::getCalendarEvent($model);

        if(!$event) {
            throw new HttpException(400, 'Invalid model given.');
        }

        if(!$event->isUpdatable()) {
            throw new HttpException(400, 'Event can not be updated by current user.');
        }

        $start = new DateTime(Yii::$app->request->post('start'));
        $end = new DateTime(Yii::$app->request->post('end'));

        if(!$event->isAllDay()) {
            $start->setTimezone(CalendarUtils::getSystemTimeZone());
            $end->setTimezone(CalendarUtils::getSystemTimeZone());
        }

        $result = $event->updateTime($start, $end);

        if(is_bool($result)) {
            return $this->asJson(['success' => $result]);
        }

        return $this->asJson(['success' => false, 'error' => $result]);
    }

}