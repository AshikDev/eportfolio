<?php
/**
 * This file provides to overwrite the default HumHub / Yii configuration by your local common (Console and Web) environments
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-configurations.html
 * @see http://docs.humhub.org/admin-installation-configuration.html
 * @see http://docs.humhub.org/dev-environment.html
 */
return [
    'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => false,
        ],
        'authClientCollection' => [
            'clients' => [
                // ...
                'github' => [
                    'class' => 'humhub\modules\user\authclient\GitHub',
                    'clientId' => '0497598c6c13291ebfb1',
                    'clientSecret' => 'f5ce872c4488e801ff5c40f259277cbfd90dd7c9',
                    // require read access to the users email
                    // https://developer.github.com/v3/oauth/#scopes
                    'scope' => 'user:email',
                ],
                'orcid' => [
                    'class' => 'humhub\modules\user\authclient\Orcid',
                    'clientId' => 'APP-QCP04G0DPF43TFNX',//'APP-3SHZL0PW6DMGRWAM',
                    'clientSecret' => 'de960cb3-8e9a-4fa6-9ecd-8751f180f3d8',//'d36504f4-a9a1-4f73-9372-6ebdcf5111e9',
                    // require read access to the users email
                    // https://developer.github.com/v3/oauth/#scopes
                    'scope' => '/authenticate',
                ],
                /*'linkedin' => [
                    'class' => 'humhub\modules\user\authclient\LinkedIn',
                    'clientId' => '779biaa3oqmjex',
                    'clientSecret' => 'jFPnUy2j6E4XwOik'
                ],*/
            ],
        ],
    ]
];
