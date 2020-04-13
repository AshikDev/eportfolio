<?php

namespace humhub\modules\calendar\controllers;

use humhub\modules\content\models\ContentContainer;
use humhub\modules\space\models\Space;
use Yii;
use yii\web\HttpException;
use humhub\modules\calendar\models\DefaultSettings;
use humhub\modules\calendar\models\forms\CalendarEntryFormCustom;
use humhub\modules\calendar\permissions\ManageEntry;
use humhub\modules\stream\actions\Stream;
use humhub\widgets\ModalClose;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\calendar\permissions\CreateEntry;
use humhub\modules\calendar\models\CalendarEntry;
use humhub\modules\calendar\models\CalendarEntryParticipant;

/**
 * EntryController used to display, edit or delete calendar entries
 *
 * @package humhub.modules_core.calendar.controllers
 * @author luke
 */
class EntrycustomController extends ContentContainerController
{

    /**
     * @inheritdoc
     */
    public $hideSidebar = true;

    public function actionView($id, $cal = null)
    {
        $entry = $this->getCalendarEntry($id);

        if (!$entry) {
            throw new HttpException('404');
        }

        // We need the $cal information, since the edit redirect in case of fullcalendar view is other than stream view
        if ($cal) {
            return $this->renderModal($entry, $cal);
        }

        return $this->render('view', ['entry' => $entry, 'stream' => true]);
    }

    private function renderModal($entry, $cal)
    {
        return $this->renderAjax('modal', [
            'entry' => $entry,
            'editUrl' => $this->contentContainer->createUrl('/calendar/entrycustom/edit', ['id' => $entry->id, 'cal' => $cal]),
            'canManageEntries' => $entry->content->canEdit() || $this->canManageEntries(),
            'contentContainer' => $this->contentContainer,
        ]);
    }

    /**
     * @param $id
     * @param $type
     * @return \yii\web\Response
     * @throws HttpException
     */
    public function actionRespond($id, $type)
    {
        $calendarEntry = $this->getCalendarEntry($id);

        if ($calendarEntry == null) {
            throw new HttpException('404');
        }

        $participationState = $calendarEntry->respond((int)$type);
        if($participationState->hasErrors()) {
            return $this->asJson(['success' => false, 'errors' => $participationState->getErrors()]);
        }
        return $this->asJson(['success' => true]);
    }

    public function actionEdit($id = null, $start = null, $end = null, $cal = null)
    {
        if (empty($id) && $this->canCreateEntries()) {
            $calendarEntryForm = new CalendarEntryFormCustom();
            $calendarEntryForm->createNew($this->contentContainer, $start, $end);

        } else {
            $calendarEntryForm = new CalendarEntryFormCustom(['entry' => $this->getCalendarEntry($id)]);
            if(!$calendarEntryForm->entry->content->canEdit()) {
                throw new HttpException(403);
            }
        }

        if (!$calendarEntryForm->entry) {
            throw new HttpException(404);
        }

        if ($calendarEntryForm->load(Yii::$app->request->post()) && $calendarEntryForm->save()) {

            if($calendarEntryForm->to_be_continued) {
                $numberOfEvents = $calendarEntryForm->number_of_events;
                for ( $i = 1; $i < $numberOfEvents; $i++ ) {

                    $interval = "";
                    switch ($calendarEntryForm->week) {
                        case 1:
                            $interval = '+' . $i . " week";
                            $next = strtotime(date("n/j/y", strtotime($calendarEntryForm->start_date)) . $interval);
                            $nextEnd = strtotime(date("n/j/y", strtotime($calendarEntryForm->end_date)) . $interval);
                            break;
                        case 2:
                            $interval = '+' . $i * 2 . " weeks";
                            $next = strtotime(date("n/j/y", strtotime($calendarEntryForm->start_date)) . $interval);
                            $nextEnd = strtotime(date("n/j/y", strtotime($calendarEntryForm->end_date)) . $interval);
                            break;
                        case 3:
                            $interval = '+' . $i . " month";
                            $next = strtotime(date("n/j/y", strtotime($calendarEntryForm->start_date)) . $interval);
                            $nextEnd = strtotime(date("n/j/y", strtotime($calendarEntryForm->end_date)) . $interval);
                            break;
                        default:
                            break;
                    }

                    $nextWeek = date('n/j/y', $next);
                    $nextWeekEnd = date('n/j/y', $nextEnd);

                    $calendarRecursive = new CalendarEntryFormCustom();
                    $calendarRecursive->createNew($this->contentContainer, $start, $end);
                    $calendarRecursive->load(Yii::$app->request->post());
                    $calendarRecursive->start_date = $nextWeek;
                    $calendarRecursive->end_date = $nextWeekEnd;
                    $calendarRecursive->save();
                }
            }

//            if($calendarEntryForm->synchronize) {
//                if($this->contentContainer->community != '_0_') {
//
//                    $parentsFormat = trim($this->contentContainer->community, '_');
//                    $spaceParentsIds = explode('_', $parentsFormat);
//
//                    $spaceParents = Space::find()
//                        ->select('guid')
//                        ->where(['in', 'id', $spaceParentsIds])
//                        ->all();
//
//                    if(isset($spaceParents) && !empty($spaceParents)) {
//                        foreach ($spaceParents as $spaceParent) {
//
//                            $contentContainerParent = ContentContainer::find()->where(['guid' => $spaceParent->guid])->one();
//
//                            if(!empty($contentContainerParent)) {
//
//                                $container = $contentContainerParent->getPolymorphicRelation();
//
//                                $calendarEntryFormParent = new CalendarEntryFormCustom();
//                                $calendarEntryFormParent->createNew($container, $start, $end);
//                                $calendarEntryFormParent->load(Yii::$app->request->post());
//                                $calendarEntryFormParent->save();
//                            }
//
//                        }
//                    }
//
//                } else {
//
//                    $spaceChilds = Space::find()
//                        ->select('guid')
//                        ->filterWhere(['like', 'community', '%\_'. $this->contentContainer->id . '\_%', false])
//                        ->all();
//
//                    if(isset($spaceChilds) && !empty($spaceChilds)) {
//                        foreach ($spaceChilds as $spaceChild) {
//
//                            $contentContainerChild = ContentContainer::find()->where(['guid' => $spaceChild->guid])->one();
//
//                            if(!empty($contentContainerChild)) {
//
//                                $container = $contentContainerChild->getPolymorphicRelation();
//
//                                $calendarEntryFormChild = new CalendarEntryFormCustom();
//                                $calendarEntryFormChild->createNew($container, $start, $end);
//                                $calendarEntryFormChild->load(Yii::$app->request->post());
//                                $calendarEntryFormChild->save();
//                            }
//
//                        }
//                    }
//
//                }
//            }

            if(empty($cal)) {
                return ModalClose::widget(['saved' => true]);
            } else {
                return $this->renderModal($calendarEntryForm->entry, 1);
            }
        }

        return $this->renderAjax('edit', [
            'calendarEntryForm' => $calendarEntryForm,
            'contentContainer' => $this->contentContainer,
            'editUrl' => $this->contentContainer->createUrl('/calendar/entrycustom/edit', ['id' => $calendarEntryForm->entry->id, 'cal' => $cal])
        ]);
    }

    public function actionToggleClose($id)
    {
        $entry = $this->getCalendarEntry($id);

        if(!$entry) {
            throw new HttpException(404);
        }

        if(!$entry->content->canEdit()) {
            throw new HttpException(403);
        }

        $entry->toggleClosed();

        return $this->asJson(Stream::getContentResultEntry($entry->content));
    }

    public function actionEditAjax()
    {
        $this->forcePostRequest();

        $entry = $this->getCalendarEntry(Yii::$app->request->post('id'));

        if (!$entry) {
            throw new HttpException('404');
        }

        if (!($this->canManageEntries() || $entry->content->canEdit())) {
            throw new HttpException('403');
        }

        $entryForm = new CalendarEntryFormCustom(['entry' => $entry]);

        if ($entryForm->updateTime(Yii::$app->request->post('start'), Yii::$app->request->post('end'))) {
            return $this->asJson(['success' => true]);
        }

        throw new HttpException(400, "Could not save! " . print_r($entry->getErrors()));
    }

    public function actionUserList()
    {
        $calendarEntry = $this->getCalendarEntry(Yii::$app->request->get('id'));

        if ($calendarEntry == null) {
            throw new HttpException('404', Yii::t('CalendarModule.base', "Event not found!"));
        }
        $state = Yii::$app->request->get('state');

        $query = User::find();
        $query->leftJoin('calendar_entry_participant', 'user.id=calendar_entry_participant.user_id AND calendar_entry_participant.calendar_entry_id=:calendar_entry_id AND calendar_entry_participant.participation_state=:state', [
            ':calendar_entry_id' => $calendarEntry->id,
            ':state' => $state
        ]);
        $query->where('calendar_entry_participant.id IS NOT NULL');

        $title = "";
        if ($state == CalendarEntryParticipant::PARTICIPATION_STATE_ACCEPTED) {
            $title = Yii::t('CalendarModule.base', 'Attending users');
        } elseif ($state == CalendarEntryParticipant::PARTICIPATION_STATE_DECLINED) {
            $title = Yii::t('CalendarModule.base', 'Declining users');
        } elseif ($state == CalendarEntryParticipant::PARTICIPATION_STATE_MAYBE) {
            $title = Yii::t('CalendarModule.base', 'Maybe attending users');
        }
        return $this->renderAjaxContent(UserListBox::widget(['query' => $query, 'title' => $title]));
    }

    public function actionDelete()
    {
        $this->forcePostRequest();

        $calendarEntry = $this->getCalendarEntry(Yii::$app->request->get('id'));

        if ($calendarEntry == null) {
            throw new HttpException('404', Yii::t('CalendarModule.base', "Event not found!"));
        }

        if (!($this->canManageEntries() ||  $calendarEntry->content->canEdit())) {
            throw new HttpException('403', Yii::t('CalendarModule.base', "You don't have permission to delete this event!"));
        }

        $calendarEntry->delete();

        if (Yii::$app->request->isAjax) {
            $this->asJson(['success' => true]);
        } else {
            return $this->redirect($this->contentContainer->createUrl('/calendar/view/index'));
        }
    }

    /**
     * Returns a readable calendar entry by given id
     *
     * @param int $id
     * @return CalendarEntry
     */
    protected function getCalendarEntry($id)
    {
        return CalendarEntry::find()->contentContainer($this->contentContainer)->readable()->where(['calendar_entry.id' => $id])->one();
    }

    /**
     * Checks the CreatEntry permission for the given user on the given contentContainer.
     * @return bool
     */
    private function canCreateEntries()
    {
        return $this->contentContainer->permissionManager->can(CreateEntry::class);
    }

    /**
     * Checks the ManageEntry permission for the given user on the given contentContainer.
     *
     * Todo: After 1.2.1 use $entry->content->canEdit();
     *
     * @return bool
     */
    private function canManageEntries()
    {
        return $this->contentContainer->permissionManager->can(new ManageEntry);
    }

    public function actionGenerateics()
    {
        $calendarEntry = $this->getCalendarEntry(Yii::$app->request->get('id'));
        $ics = $calendarEntry->generateIcs();
        return Yii::$app->response->sendContentAsFile($ics, uniqid() . '.ics', ['mimeType' => 'text/calendar']);
    }
}
