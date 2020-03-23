<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\calendar\widgets;


use humhub\components\Widget;
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\ArrayHelper;

class CalendarFilterBarCustom extends Widget
{
    public $filters = [];
    public $selectors = [];

    public $showFilter = true;
    public $showSelectors = true;

    public $canConfigure = false;
    public $configUrl;
    public $community = '';
    public $space_id = 0;

    public function run()
    {
        if(Yii::$app->user->isGuest) {
            return;
        }
        $user = Yii::$app->user->getIdentity();

        if ( $this->community != '' ) {
            $spaceModelAll = (new \yii\db\Query())
                ->select("sm.id as id, sm.name as name, sm.community as community")
                ->from('space_membership')
                ->leftJoin('space sm', 'sm.id=space_membership.space_id')
                ->filterWhere(['like', 'sm.community', '%\_'. $this->space_id . '\_%', false])
                ->andWhere('space_membership.user_id= '. $user->id .' AND space_membership.status=' . \humhub\modules\space\models\Membership::STATUS_MEMBER)
                ->all();
        } else {
            $spaceModelAll = (new \yii\db\Query())
                ->select("sm.id as id, sm.name as name, sm.community as community")
                ->from('space_membership')
                ->leftJoin('space sm', 'sm.id=space_membership.space_id')
                ->andWhere('space_membership.user_id= '. $user->id .' AND space_membership.status=' . \humhub\modules\space\models\Membership::STATUS_MEMBER)
                ->all();
        }

        return $this->render('calendarFilterBarCustom', [
            'filters' => $this->filters,
            'canConfigure' => $this->canConfigure,
            'configUrl' => $this->configUrl,
            'selectors' => $this->selectors,
            'showFilters' => $this->showFilter,
            'showSelectors' => $this->showSelectors,
            'spaceModelAll' => $spaceModelAll,
            'spaceId' => $this->space_id
        ]);
    }
}