<?php

namespace humhub\modules\freichat;

use Exception;
use humhub\modules\freichat\widgets\FreiChatBar;
use Yii;

class Events extends \yii\base\BaseObject
{
    public static function loadFreiChat($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addWidget(FreiChatBar::class, [], []);
    }

    public static function saveToken($event)
    {
        $token = Yii::$app->getModule('freichat')->settings->get('token');
        if (empty($token)) {
            $baseUrl = "https://nodelb.freichat.com/api";
            $tokenData = Events::generateToken($baseUrl);
            $settings = Yii::$app->getModule('freichat')->settings;

            if ($tokenData['error'] == NULL) {
                $settings->set('token', $tokenData['key']);
            } else {
                $settings->set('error', $tokenData['error']);
            }
            $settings->set('baseUrl', $baseUrl);
        }
    }

    /**
     * Generates a token for secure communication
     * If there is some error during token generation, that error is saved in module settings in db
     * @return array
     *
     * @since version
     */
    private static function generateToken($baseUrl)
    {
        $data = array(
            'domain' => $_SERVER['HTTP_HOST'],
            'platform' => 'Humhub'
        );

        $payload = json_encode($data);

        require_once 'config.php';

        try {
            $ch = curl_init("$baseUrl/v1/keys/free/register");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

            // Submit the POST request
            $key = curl_exec($ch);
        } catch (Exception $e) {
            return array("key" => null, "error" => $e->getMessage());
        }

        return array("key" => $key, "error" => null);
    }
}
