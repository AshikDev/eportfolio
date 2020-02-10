<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\autopatch\components;


use Yii;
use yii\base\Component;
use yii\helpers\Json;

/**
 * Class Patch
 * @package humhub\modules\autopatch\components
 */
class PatchManager extends Component
{

    /**
     * @var PatchInfo[] all available patches for this version
     */
    protected $availablePatches = null;


    /**
     * Returns a list of all HumHub patches which are suitable for this version
     *
     * @return PatchInfo[]
     */
    public function getAvailable()
    {
        if ($this->availablePatches !== null) {
            return $this->availablePatches;
        }

        $patches = [];
        if (class_exists('\humhub\modules\admin\libs\HumHubAPI')) {
            $patches = \humhub\modules\admin\libs\HumHubAPI::request('v1/patch/list', []);
        } else {
            // older Versions
            $url = 'https://api.humhub.com/v1/patch/list';
            $http = new \Zend\Http\Client($url, [
                'adapter' => '\Zend\Http\Client\Adapter\Curl',
                'curloptions' => Yii::$app->getModule('auto-patch')->getCurlOptions(),
                'timeout' => 30
            ]);
            $response = $http->send();
            $patches = Json::decode($response->getBody());
        }

        $this->availablePatches = [];
        foreach ($patches as $patch) {
            if (version_compare(Yii::$app->version, $patch['maxVersion'], '<=') && version_compare(Yii::$app->version, $patch['minVersion'], '>=')) {
                $this->availablePatches[$patch['id']] = new PatchInfo([
                    'id' => $patch['id'],
                    'name' => $patch['name'],
                    'description' => $patch['description'],
                    'downloadUrl' => $patch['downloadUrl'],
                    'md5' => $patch['md5'],
                    'minVersion' => $patch['minVersion'],
                    'maxVersion' => $patch['maxVersion']
                ]);
            }
        }
        return $this->availablePatches;
    }


    /**
     * Returns a patch by id
     *
     * @param $id
     * @return PatchInfo|null
     */
    public function getById($id)
    {
        $availablePatches = $this->getAvailable();
        if (isset($availablePatches[$id])) {
            return $availablePatches[$id];
        }

        return null;
    }

}