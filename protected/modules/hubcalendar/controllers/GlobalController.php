<?php

namespace humhub\modules\hubcalendar\controllers;

use DateTime;
use humhub\components\access\StrictAccess;
use humhub\components\Controller;
use humhub\modules\hubcalendar\helpers\CalendarUtils;
use humhub\modules\hubcalendar\interfaces\CalendarService;
use humhub\modules\hubcalendar\interfaces\event\AbstractCalendarQuery;
use humhub\modules\hubcalendar\models\fullcalendar\FullCalendar;
use humhub\modules\hubcalendar\models\SnippetModuleSettings;
use humhub\modules\hubcalendar\permissions\CreateEntry;
use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentContainerModuleManager;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use Yii;
use humhub\modules\hubcalendar\helpers\Url;
use yii\web\HttpException;

/**
 * GlobalController provides a global view.
 *
 * @package humhub.modules_core.hubcalendar.controllers
 * @author luke
 */
class GlobalController extends Controller
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
     * @inheritdocs
     */
    public $access = StrictAccess::class;

    /**
     * @return array
     */
    public function getAccessRules()
    {
        return [
            ['login' => ['enable', 'select']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->calendarService = $this->module->get(CalendarService::class);
    }

    public function beforeAction($action)
    {
        if (!SnippetModuleSettings::instantiate()->showGlobalCalendarItems()) {
            throw new HttpException('500', 'Calendar module is not enabled on your profile!');
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $moduleEnabled = Yii::$app->user->getIdentity()->isModuleEnabled('hubcalendar');
        } else {
            $moduleEnabled = false;
            $configureUrl = null;
        }

        return $this->render('index', [
            'selectors' => $this->getSelectorSettings(),
            'filters' => $this->getFilterSettings(),
            'canConfigure' => $moduleEnabled,
            'editUrl' => Url::to(['/hubcalendar/entry/edit'])
        ]);
    }

    public function actionSelect($start = null, $end = null)
    {
        /* @var $user User */
        $contentContainerSelection = [];
        $user = Yii::$app->user->getIdentity();

        $contentContainerSelection[$user->contentcontainer_id] = Yii::t('CalendarModule.base', 'Profile Calendar');

        $calendarMemberSpaceQuery = Membership::getUserSpaceQuery(Yii::$app->user->getIdentity());

        if(!ContentContainerModuleManager::getDefaultState(Space::class, 'hubcalendar')) {
            $calendarMemberSpaceQuery->leftJoin('contentcontainer_module',
                'contentcontainer_module.module_id = :calendar AND contentcontainer_module.contentcontainer_id = space.contentcontainer_id',
                [':calendar' => 'hubcalendar']
            )->andWhere('contentcontainer_module.module_id IS NOT NULL');
        }

        foreach ($calendarMemberSpaceQuery->all() as $space) {
            if ($space->permissionManager->can(CreateEntry::class)) {
                $contentContainerSelection[$space->contentcontainer_id] = $space->displayName;
            }
        }

        return $this->renderAjax('selectContainerModal', [
            'contentContainerSelection' => $contentContainerSelection,
            'submitUrl' => Url::to(['/hubcalendar/global/select-submit', 'start' => $start, 'end' => $end]),
        ]);
    }

    public function actionSelectSubmit($start = null, $end = null)
    {
        $this->forcePostRequest();

        $contentContainer = ContentContainer::findOne(['id' => Yii::$app->request->post('contentContainerId')]);

        if (empty($contentContainer)) {
            throw new HttpException(404);
        }

        $container = $contentContainer->getPolymorphicRelation();

        if (!$container->permissionManager->can(CreateEntry::class)) {
            throw new HttpException(403);
        }

        if ($container instanceof User && $container->is(Yii::$app->user->getIdentity())) {
            if (!$container->isModuleEnabled('hubcalendar')) {
                return Yii::$app->runAction('/hubcalendar/global/enable', ['start' => $start, 'end' => $end]);

                /**
                 *TODO: automatically enable the hubcalendar module in profile
                 * $container->enableModule('hubcalendar');
                 *
                 *TODO: should be handle by the core
                 *Yii::$app->cache->get(\humhub\modules\user\models\Module::STATES_CACHE_ID_PREFIX . $container->id);
                 *Yii::$app->user->getIdentity()->_enabledModules = null;
                 **/
            }
        }

        $params = ($container instanceof User) ? ['uguid' => $container->guid] : ['sguid' => $container->guid];
        $params['start'] = $start;
        $params['end'] = $end;
        $params['cal'] = 1;

        Yii::$app->request->setQueryParams($params);

        return Yii::$app->runAction('/hubcalendar/entry/edit', $params);
    }

    /**
     * @return array|mixed hubcalendar selector settings
     * @throws \Throwable
     */
    private function getSelectorSettings()
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        $selectors = $this->getUserSettings()->getSerialized('lastSelectors', [
            ActiveQueryContent::USER_RELATED_SCOPE_OWN_PROFILE,
            ActiveQueryContent::USER_RELATED_SCOPE_SPACES,
        ]);

        return $selectors;
    }

    /**
     * @return array|mixed hubcalendar filter settings
     * @throws \Throwable
     */
    private function getFilterSettings()
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        return $this->getUserSettings()->getSerialized('lastFilters', []);
    }

    /**
     * @return \humhub\modules\content\components\ContentContainerSettingsManager
     */
    public function getUserSettings()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        /* @var $module \humhub\modules\hubcalendar\Module */
        $module = Yii::$app->getModule('hubcalendar');
        return $module->settings->user();
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
        $output = [];

        if (!Yii::$app->user->isGuest) {
            $settings =  $this->getUserSettings();

            $selectors = Yii::$app->request->get('selectors', []);
            $filters = Yii::$app->request->get('filters', []);

            $settings->setSerialized('selectors', $selectors);
            $settings->setSerialized('filters', $filters);

            $filters['userRelated'] = $selectors;

            $entries = $this->calendarService->getCalendarItems(new DateTime($start), new DateTime($end), $filters);
        } else {
            $entries = $this->calendarService->getCalendarItems(new DateTime($start), new DateTime($end));
        }

        foreach ($entries as $entry) {
            $output[] = FullCalendar::getFullCalendarArray($entry);
        }

        return $this->asJson($output);
    }

    public function actionEnable($start, $end)
    {
        $user = Yii::$app->user->getIdentity();

        $cancelButton = ModalButton::cancel();
        $enableButton = ModalButton::primary(Yii::t('CalendarModule.base', 'Enable'))
            ->action('content.container.enableModule', Url::toEnableProfileModule($user));

        $nextButton = ModalButton::primary(Yii::t('CalendarModule.base', 'Next'))
            ->load(Url::toCreateEntry($user, $start, $end))->style('display:none')->cssClass('disable')->loader(true);


        return ModalDialog::widget([
            'header' => Yii::t('CalendarModule.base', '<strong>Add</strong> profile calendar'),
            'body' => Yii::t('CalendarModule.base', 'In order to add events to your profile, you have to enable the calendar module first.'),
            'footer' => $enableButton . $nextButton . $cancelButton,
            'centerText' => true
        ]);
    }

    public function actionEnableConfig()
    {
        $user = Yii::$app->user->getIdentity();

        $cancelButton = ModalButton::cancel();
        $enableButton = ModalButton::primary(Yii::t('CalendarModule.base', 'Enable'))
            ->action('content.container.enableModule', Url::toEnableProfileModule($user));

        $nextButton = ModalButton::primary(Yii::t('CalendarModule.base', 'Next'))
            ->link(Url::toConfig($user))->style('display:none')
            ->cssClass('disable')
            ->loader(true)
            ->close();

        return ModalDialog::widget([
            'header' => Yii::t('CalendarModule.base', '<strong>Add</strong> profile calendar'),
            'body' => Yii::t('CalendarModule.base', 'Do you want to install this module on your profile?'),
            'footer' => $enableButton . $nextButton . $cancelButton,
            'centerText' => true
        ]);
    }
}
