<?php
namespace humhub\modules\calendar\models;

use humhub\modules\calendar\interfaces\AbstractCalendarQueryCustom;

class CalendarEntryQueryCustom extends AbstractCalendarQueryCustom
{
    /**
     * @inheritdocs
     */
    protected static $recordClass = CalendarEntry::class;

    /**
     * @var bool true if the participant join has already been added else false
     */
    private $praticipantJoined = false;

    public function filterResponded()
    {
        $this->participantJoin();
        $this->_query->andWhere(['IS NOT', 'calendar_entry_participant.id', new \yii\db\Expression('NULL')]);
    }

    public function filterNotResponded()
    {
        $this->participantJoin();
        $this->_query->andWhere(['IS', 'calendar_entry_participant.id', new \yii\db\Expression('NULL')]);
    }

    public function filterIsParticipant()
    {
        $this->participantJoin();
        $this->_query->andWhere(['calendar_entry_participant.participation_state' => CalendarEntryParticipant::PARTICIPATION_STATE_ACCEPTED]);
    }

    private function participantJoin()
    {
        if(!$this->praticipantJoined) {
            $this->_query->leftJoin('calendar_entry_participant', 'calendar_entry.id=calendar_entry_participant.calendar_entry_id AND calendar_entry_participant.user_id=:userId', [':userId' => $this->_user->id]);
            $this->praticipantJoined = true;
        }
    }


}
