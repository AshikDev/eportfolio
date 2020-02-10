<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard\widgets;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\widgets\BaseStack;


/**
 * Class VCardAddons
 * @package humhub\modules\popovervcard\widgets
 */
class VCardAddons extends BaseStack
{
    /**
     * @var ContentContainerActiveRecord
     */
    public $container;

    /**
     * {inheritdoc}
     */
    public $seperator = '<br><hr><br>';

}