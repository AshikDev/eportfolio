<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard\widgets;

use humhub\components\Widget;
use humhub\modules\popovervcard\Module;
use humhub\modules\space\models\Membership;
use Yii;


/**
 * Class VCardUser
 * @package humhub\modules\popovervcard\widgets
 */
class VCardSpace extends Widget
{
    public $space;

    public function run()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('popover-vcard');

        $memberCount = Membership::getSpaceMembersQuery($this->space)->count();

        $twig = new \Twig_Environment(new \Twig_Loader_String());
        $templateParams = ['space' => $this->space, 'memberCount' => $memberCount];

        $description = '';
        try {
            $description = $twig->render($module->getConfiguration()->spaceContent, $templateParams);
        } catch (\Twig_Error_Loader $e) {
            $description = $e->getMessage();
        } catch (\Twig_Error_Runtime $e) {
            $description = $e->getMessage();
        } catch (\Twig_Error_Syntax $e) {
            $description = $e->getMessage();
        }

        return $this->render('vcard-space', [
            'space' => $this->space,
            'description' => $description,
            'memberCount' => $memberCount
        ]);
    }

}