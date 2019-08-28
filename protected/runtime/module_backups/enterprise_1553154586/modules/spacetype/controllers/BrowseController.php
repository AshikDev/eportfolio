<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\modules\spacetype\controllers;

use Yii;
use yii\helpers\Html;
use humhub\modules\space\widgets\Image;

/**
 * Description of BrowseController
 *
 * @author buddha
 */
class BrowseController extends \humhub\modules\space\controllers\BrowseController
{

    protected function prepareResult($searchResultSet)
    {
        $target = Yii::$app->request->get('target');
        
        $json = [];
        $withChooserItem = ($target === 'chooser');
        foreach ($searchResultSet->getResultInstances() as $space) {
            $json[] = $this->getSpaceResult($space, $withChooserItem);
        }

        return $json;
    }
    
    public function getSpaceResult($space, $withChooserItem = true, $options = [])
    {
        $spaceInfo = [];
        $spaceInfo['guid'] = $space->guid;
        $spaceInfo['title'] = Html::encode($space->name);
        $spaceInfo['tags'] = Html::encode($space->tags);
        $spaceInfo['image'] = Image::widget(['space' => $space, 'width' => 24]);
        $spaceInfo['link'] = $space->getUrl();

        if ($withChooserItem) {
            $options = array_merge(['space' => $space, 'isMember' => false, 'isFollowing' => false], $options);
            $spaceInfo['output'] = \humhub\modules\enterprise\modules\spacetype\widgets\SpaceChooserItem::widget($options);
        }

        return $spaceInfo;
    }
    

}
