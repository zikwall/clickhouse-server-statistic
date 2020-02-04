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
            ],
            [
                'url' => 'register',
                'path' => 'user/registration/register222',
                'verb' => ['POST', 'OPTIONS']
            ],
            [
                'url' => 'register/confirm',
                'path' => 'rest/user/user/confirm',
                'verb' => ['GET', 'OPTIONS']
            ],
            [
                'url' => 'register/unconfirm',
                'path' => 'rest/user/user/unconfirm',
                'verb' => ['GET', 'OPTIONS']
            ],
            [
                'url' => 'create',
                'path' => 'rest/user/user/create-user',
                'verb' => ['POST', 'OPTIONS']
            ],
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
            [
                'pattern'   => 'get-ads-data-of-partner-channels',
                'route'     => 'rest/ads/ads/get-ads-data-of-partner-channels',
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
            [
                'pattern'   => 'api/v1/general/get-month-users',
                'route'     => 'rest/app_users/app-users/get-month-users',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-user-intersection',
                'route'     => 'rest/app_users/app-users/get-user-intersection-android',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-channels-uniq-users-by-account',
                'route'     => 'rest/channels/channels/get-channels-uniq-users-by-account',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-channels-view-duration-with-channels-id',
                'route'     => 'rest/channels/channels/get-channels-view-duration-with-channels-id',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-start-channels-of-partner',
                'route'     => 'rest/channels/channels/get-start-channels-of-partner',
                'verb'      => ['POST', 'OPTIONS']
            ],
            [
                'pattern'   => 'api/v1/general/get-channels-by-gadget-types',
                'route'     => 'rest/channels/channels/get-channels-by-gadget-types',
                'verb'      => ['POST', 'OPTIONS'],
            ]
                /*
            [
                'pattern'   => 'api/v1/general/get-chef-parameter',
                'route'     => 'rest/channels/channels/get-chef-parameter',
                'verb'      => ['POST', 'OPTIONS']
            ],
            */
        ]);

        Router::addRoute(
            'api/v1/channels/load',
            'rest/channels/load/day',
            ['POST', 'GET']
        );
        
        Router::addGroupUrl('api/v1/user', [
            [
                'url' => 'channels/update',
                'path' => 'rest/user/user/user-channels-update',
                'verb' => ['POST', 'OPTIONS']
            ],
            [
                'url' => 'list',
                'path' => 'rest/user/user/get-users',
                'verb' => ['GET', 'OPTIONS']
            ],
            [
                'url' => 'channels/list',
                'path' => 'rest/user/user/get-user-channels',
                'verb' => ['GET', 'OPTIONS']
            ],
        ]);

        Router::init();
    }
}