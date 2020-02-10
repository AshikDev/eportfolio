<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\autopatch\components;


use Yii;
use yii\base\Component;
use humhub\modules\autopatch\Module;

/**
 * Class PatchInfo
 *
 * @package humhub\modules\autopatch\components
 */
abstract class Patch extends Component
{
    /**
     * @var PatchInfo
     */
    public $patchInfo = null;


    /**
     * @var string
     */
    public $errorMessage;


    /**
     * Applies the path
     *
     * @return boolean
     */
    abstract public function apply();


    /**
     * Checks if the patch is already applied
     *
     * @return bool
     */
    public function isApplied()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('auto-patch');
        return ($module->settings->get($this->getSettingAppliedKey()) === 'applied');
    }


    /**
     * Marks this patch as applied
     */
    public function markAsApplied()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('auto-patch');
        $module->settings->set($this->getSettingAppliedKey(), 'applied');
    }

    /**
     * Returns the settings key name to store the information that this patch was already applied
     *
     * @internal
     * @return string the settings key name
     */
    protected function getSettingAppliedKey()
    {
        return get_class($this) . '_' . Yii::$app->version;
    }

}