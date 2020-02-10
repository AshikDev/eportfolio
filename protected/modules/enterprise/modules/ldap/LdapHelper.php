<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2019 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\modules\ldap;


use Yii;

/**
 * Class LdapHelper
 * @package humhub\modules\enterprise\modules\ldap
 */
class LdapHelper
{

    public static function isLdapEnabled()
    {

        foreach (Yii::$app->authClientCollection->getClients() as $authClient) {

            if (method_exists($authClient, 'getLdap')) {
                return true;
            }
        }

        return false;

    }

}