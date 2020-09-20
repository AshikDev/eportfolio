<?php

namespace humhub\modules\hubcalendar\controllers;

use humhub\modules\hubcalendar\interfaces\event\AbstractCalendarQuery;
use Yii;
use humhub\modules\hubcalendar\helpers\CalendarUtils;
use humhub\modules\hubcalendar\models\fullcalendar\FullCalendar;
use DateTime;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use humhub\modules\hubcalendar\permissions\CreateEntry;
use humhub\modules\content\components\ContentContainerController;
use yii\base\InvalidConfigException;

/**
 * ViewController displays the hubcalendar on spaces or user profiles.
 *
 * @package humhub.modules_core.hubcalendar.controllers
 * @author luke
 */
class ViewController extends ContentContainerController
{

    /**
     * @inheritdoc
     */
    public $hideSidebar = true;

    /**
     * @var CalendarService
     */
    public $calendarService;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module->set(CalendarServiceCustom::class, ['class' => CalendarServiceCustom::class]);
        $this->calendarService = $this->module->get(CalendarService::class);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'canAddEntries' => $this->contentContainer->permissionManager->can(new CreateEntry()),
            'filters' => [],
        ]);
    }

    /**
     * Loads entries within search interval, the given string contains timezone offset.
     *
     * @param $start string search start time e.g: '2019-12-30T00:00:00+01:00'
     * @param $end string search end time e.g: '2020-02-10T00:00:00+01:00'
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionLoadAjax($start, $end)
    {
        $result = [];

        $filters = Yii::$app->request->get('filters', []);

        foreach ($this->calendarService->getCalendarItems( new DateTime($start), new DateTime($end), $filters, $this->contentContainer) as $entry) {
            $result[] = FullCalendar::getFullCalendarArray($entry);
        }

        return $this->asJson($result);
    }
}
