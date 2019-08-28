<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\autopatch\components;


use humhub\modules\autopatch\Module;
use Yii;
use yii\base\Component;
use ZipArchive;

/**
 * Class PatchInfo
 *
 * @package humhub\modules\autopatch\components
 */
class PatchInfo extends Component
{
    public $id;

    public $name;
    public $description;

    public $installTime;
    public $downloadUrl;

    public $minVersion;
    public $maxVersion;

    public $md5;


    /**
     * @return Patch the Patch object
     * @throws \yii\base\InvalidConfigException
     */
    public function getPatch()
    {
        $this->download();
        $this->extract();

        // Load Patch class
        require_once $this->getPackageDirectory() . DIRECTORY_SEPARATOR . $this->id . '.php';

        return Yii::createObject([
            'class' => $this->id,
            'patchInfo' => $this
        ]);
    }


    public function isApplied()
    {
        $patch = $this->getPatch();
        if ($patch !== null && $patch->isApplied()) {
            return true;
        }

        return false;
    }

    protected function download()
    {
        $targetFile = $this->getPackageFile();

        // Already downloaded
        if (is_file($targetFile) && md5_file($targetFile) == $this->md5) {
            return;
        }

        try {
            $http = new \Zend\Http\Client($this->downloadUrl, [
                'adapter' => '\Zend\Http\Client\Adapter\Curl',
                'curloptions' => Yii::$app->getModule('auto-patch')->getCurlOptions(),
                'timeout' => 600
            ]);
            $http->setStream();
            $response = $http->send();
            copy($response->getStreamName(), $targetFile);
        } catch (\Exception $ex) {
            throw new \Exception(Yii::t('AutoPatchModule.base', 'Patch download failed! (%error%)', array('%error%' => $ex->getMessage())));
        }

        if (md5_file($targetFile) != $this->md5) {
            @unlink($targetFile);
            throw new \Exception(Yii::t('AutoPatchModule.base', 'Patch package invalid!'));
        }

    }

    protected function extract()
    {
        // Already extracted
        if (is_dir(Module::getPatchesPath() . DIRECTORY_SEPARATOR . $this->id)) {
            return true;
        }

        $zip = new ZipArchive();
        $res = $zip->open($this->getPackageFile());
        if ($res === TRUE) {
            $zip->extractTo(Module::getPatchesPath());
            $zip->close();
        } else {
            throw new \Exception(Yii::t('AutoPatchModule.base', 'Could not extract update package!'));
        }

    }


    protected function getPackageFile()
    {
        return Module::getPatchesPath() . DIRECTORY_SEPARATOR . $this->id . '.zip';
    }


    protected function getPackageDirectory()
    {
        return Module::getPatchesPath() . DIRECTORY_SEPARATOR . $this->id;
    }


}