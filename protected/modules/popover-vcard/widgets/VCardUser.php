<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard\widgets;

use humhub\components\Widget;
use humhub\modules\popovervcard\Module;
use Yii;


/**
 * Class VCardUser
 * @package humhub\modules\popovervcard\widgets
 */
class VCardUser extends Widget
{
    public $user;

    public function run()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('popover-vcard');

        $twig = new \Twig_Environment(new \Twig_Loader_String());
        $templateParams = ['user' => $this->user, 'profile' => $this->user->profile];

        $description = '';
        try {
            $description = $twig->render($module->getConfiguration()->userContent, $templateParams);
        } catch (\Twig_Error_Loader $e) {
            $description = $e->getMessage();
        } catch (\Twig_Error_Runtime $e) {
            $description = $e->getMessage();
        } catch (\Twig_Error_Syntax $e) {
            $description = $e->getMessage();
        }

        return $this->render('vcard-user', [
            'user' => $this->user,
            'description' => $description
        ]);
    }

}