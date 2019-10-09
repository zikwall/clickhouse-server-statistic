<?php

namespace app\modules\rest;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Router::addTEST('timestamp', 'timestamp', ['GET']);
        Router::addTEST('query-as', 'test-query-as', ['GET']);

        Router::addGroupUrl('api/v1/auth', [
            [
                'url' => 'login',
                'path' => 'rest/auth/auth/index',
                'verb' => ['POST']
            ],
            [
                'url' => 'access',
                'path' => 'rest/user/user/access'
            ]
        ]);

        Router::addGET(
            'api/v1/autonomous-system/general',
            'rest/autonomoussystems/autonomous-system/general-information'
        );

        Router::addGET('api/v1/clickhouse/total/', 'rest/clickhouse/clickhouse/total');

        Router::addGroupUrl('api/v1/general', [
            [
                'url'   => 'get-app',
                'path'  => 'rest/general_data/general-data/get-app',
            ],
            [
                'pattern'   => 'get-period',
                'route'     => 'rest/general_data/general-data/get-period',
            ],

            [
                'pattern'   => 'get-ads-data',
                'route'     => 'rest/ads/ads/get-ads-data',
                'verb'      => ['POST']
            ],
        ]);

        Router::addRoutes([
            [
                'pattern'   => 'api/v1/general/get-app-users',
                'route'     => 'rest/app_users/app-users/get-app-users',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-app-users-total',
                'route'     => 'rest/app_users/app-users/get-app-users-total',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-time-zone-users',
                'route'     => 'rest/app_users/app-users/get-time-zone-users',
                'verb'      => ['POST', 'OPTIONS']
            ],

            //Channels Stat
            [
                'pattern'   => 'api/v1/general/get-channels-view-duration',
                'route'     => 'rest/channels/channels/get-channels-view-duration',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-start-channels',
                'route'     => 'rest/channels/channels/get-start-channels',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-channels-uniq-users',
                'route'     => 'rest/channels/channels/get-channels-uniq-users',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-start-app',
                'route'     => 'rest/channels/channels/get-start-app',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-start-all-app',
                'route'     => 'rest/channels/channels/get-start-all-app',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-channels-uniq-users-with-evtp',
                'route'     => 'rest/channels/channels/get-channels-uniq-users-with-evtp',
                'verb'      => ['POST', 'OPTIONS']
            ],
            /*
            [
                'pattern'   => 'api/v1/general/get-chef-parameter',
                'route'     => 'rest/channels/channels/get-chef-parameter',
                'verb'      => ['POST', 'OPTIONS']
            ],
            */
        ]);

        Router::init();
    }
}