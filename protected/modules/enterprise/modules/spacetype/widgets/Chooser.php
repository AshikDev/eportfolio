<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\modules\spacetype\widgets;

use Yii;
use humhub\modules\space\models\Membership;

class Chooser extends \humhub\modules\space\widgets\Chooser
{
    
    private $spaceTypes;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->spaceTypes = \humhub\modules\enterprise\modules\spacetype\models\Type::find()->orderBy(['sort_key' => SORT_ASC])->all();
    }

    /**
     * Displays / Run the Widgets
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        
        $memberships = $this->getMemberships();
        $followSpaces = $this->getFollowSpaces();

        $typeMembershipMap = [];

        foreach ($this->spaceTypes as $spaceType) {

            $typeMembershipMap[$spaceType->id] = [
                'spaceType' => $spaceType,
                'memberships' => [],
                'following' => []
            ];

            foreach ($memberships as $membership) {

                if ($membership->space->space_type_id == $spaceType->id) {
                    $typeMembershipMap[$spaceType->id]['memberships'][] = $membership;
                }
            }

            foreach ($followSpaces as $followSpace) {

                if ($followSpace->space_type_id == $spaceType->id) {
                    $typeMembershipMap[$spaceType->id]['following'][] = $followSpace;
                }
            }
        }

        return $this->render('spaceChooser', [
                    'currentSpace' => $this->getCurrentSpace(),
                    'spaceTypes' => $this->spaceTypes,
                    'createSpaceTypes' => $this->getCreateSpaceTypes(),
                    'memberships' => $memberships,
                    'followSpaces' => $followSpaces,
                    'typeMembershipMap' => $typeMembershipMap,
        ]);
    }
    
    /**
     * @return \humhub\modules\enterprise\modules\spacetype\models\SpaceType[] all space type with create permission.
     */
    public function getCreateSpaceTypes()
    {
        $types = [];

        if (!$this->canCreateSpace()) {
            return [];
        }

        foreach ($this->spaceTypes as $type) {
            if ($type->canCreateSpace()) {
                $types[] = $type;
            }
        }
        return $types;
    }
}
