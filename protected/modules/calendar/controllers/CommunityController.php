<?php

namespace humhub\modules\calendar\controllers;

use DateTime;
use humhub\modules\calendar\interfaces\CalendarService;
use humhub\modules\calendar\interfaces\CalendarServiceCustom;
use humhub\modules\space\models\Space;
use Yii;
use humhub\modules\calendar\permissions\CreateEntry;
use humhub\modules\content\components\ContentContainerController;

/**
 * ViewController displays the calendar on spaces or user profiles.
 *
 * @package humhub.modules_core.calendar.controllers
 * @author luke
 */
class CommunityController extends ContentContainerController
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
        $this->calendarService = $this->module->get(CalendarServiceCustom::class);
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'canAddEntries' => $this->contentContainer->permissionManager->can(new CreateEntry()),
            'canConfigure' => $this->canConfigure(),
            'filters' => [],
        ]);
    }

    public function actionLoadAjax($start, $end)
    {
        $result = [];

        $selectors = Yii::$app->request->get('selectors', []);
        $filters = Yii::$app->request->get('filters', []);
        $hubs = Yii::$app->request->get('hubs', []);

        $filters['userRelated'] = $selectors;
        $filters['hubs'] = $hubs;

        foreach ($this->calendarService->getCalendarItems(new DateTime($start), new DateTime($end), $filters, $this->contentContainer) as $entry) {
            $result[] = $entry->getFullCalendarArray();
        }

        return $this->asJson($result);
    }

    public function canConfigure()
    {
        if($this->contentContainer instanceof Space) {
            return $this->contentContainer->isAdmin();
        } else {
            return $this->contentContainer->isCurrentUser();
        }
    }

}
