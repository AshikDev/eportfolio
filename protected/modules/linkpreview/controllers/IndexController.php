<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\linkpreview\controllers;

use Yii;
use humhub\libs\CURLHelper;

/**
 * Description of IndexController
 *
 * @author luke
 */
class IndexController extends \humhub\components\Controller
{

    public function actionFetch()
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $url = Yii::$app->request->post('url');

        $output = '';
        $state = 'error';
        $errorMessage = '';
        try {
            $http = new \Zend\Http\Client($url, [
                'adapter' => '\Zend\Http\Client\Adapter\Curl',
                'curloptions' => CURLHelper::getOptions(),
                'timeout' => 30
            ]);

            $response = $http->send();

            /**
             * fixed get body
             */
            $body = (string) $response->getContent();
            $contentEncoding = $response->getHeaders()->get('Content-Encoding');
            if (!empty($contentEncoding)) {
                $contentEncoding = $contentEncoding->getFieldValue();
                if ($contentEncoding == 'gzip') {
                    $body = gzinflate(substr($body, 10));
                } elseif ($contentEncoding == 'deflate') {
                    $zlibHeader = unpack('n', substr($body, 0, 2));
                    if ($zlibHeader[1] % 31 == 0) {
                        $body = gzuncompress($body);
                    } else {
                        $body = gzinflate($body);
                    }
                }
            }

            $output = $body;
        } catch (\Zend\Http\Client\Adapter\Exception\RuntimeException $ex) {
            $errorMessage = $ex->getMessage();
            Yii::error('Could not connect! ' . $ex->getMessage());
        } catch (Exception $ex) {
            $errorMessage = $ex->getMessage();
            Yii::error('Could not get HumHub API response! ' . $ex->getMessage());
        }
        

        $body = "";
        if (preg_match('/(?:<head[^>]*>)(.*)<\/head>/isU', $output, $matches)) {
            $body = $matches[1];
        }

        //$body = $output;

        if (!mb_check_encoding($body, 'UTF-8')) {
            $body = utf8_encode($body);
        }

        return $this->asJson([
            'state' => $state,
            'url' => $url,
            //'output' => $output,
            'output' => $body,
            'errorMessage' => $errorMessage,
        ]);
    }

}
