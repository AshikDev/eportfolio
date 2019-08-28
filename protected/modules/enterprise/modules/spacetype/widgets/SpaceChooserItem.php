<?php

namespace humhub\modules\enterprise\modules\spacetype\widgets;

use Yii;

/**
 * Used to render a single space chooser result.
 * 
 */
class SpaceChooserItem extends \humhub\modules\space\widgets\SpaceChooserItem
{

    /**
     * @var string
     */
    public $spaceTypes;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(!$this->spaceTypes) {
            $this->spaceTypes = \humhub\modules\enterprise\modules\spacetype\models\Type::find()->orderBy(['sort_key' => SORT_ASC])->all();
        }
    }

    public function run()
    {
        $data = $this->getDataAttribute();
        $badge = $this->getBadge();

        $currentSpace = $this->getCurrentSpace();
        
        return $this->render('spaceChooserItem', [
                    'space' => $this->space,
                    'isCurrentSpace' => ($currentSpace && ($currentSpace->id === $this->space->id)),
                    'spaceType' => $this->getTypeTitle($this->space),
                    'spaceTypeID' => $this->space->space_type_id,
                    'updateCount' => $this->updateCount,
                    'visible' => $this->visible,
                    'badge' => $badge,
                    'data' => $data
        ]);
    }
    
    protected function getCurrentSpace()
    {
        if (Yii::$app->controller instanceof \humhub\modules\content\components\ContentContainerController) {
            if (Yii::$app->controller->contentContainer !== null && Yii::$app->controller->contentContainer instanceof \humhub\modules\space\models\Space) {
                return Yii::$app->controller->contentContainer;
            }
        }

        return null;
    }

    public function getTypeTitle($space)
    {
        if (!$this->spaceTypes || count($this->spaceTypes) < 2) {
            return "";
        }

        foreach ($this->spaceTypes as $type) {
            if ($type->id == $space->space_type_id) {
                return $type->item_title;
            }
        }

        return "";
    }
}
