<?php

namespace app\modules\rest;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            // Auth Block
            ['pattern' => 'api/v1/auth/login/', 'route' => 'rest/auth/auth/index', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/auth/access/', 'route' => 'rest/user/user/access', 'verb' => ['GET', 'OPTIONS']],

            // Autonomous Systems Block
            [
                'pattern' => 'api/v1/autonomous-system/general',
                'route' => 'rest/autonomoussystems/autonomous-system/general-information',
                'verb' => ['GET', 'OPTIONS']],

            // ClickHouse Block
            ['pattern' => 'api/v1/clickhouse/total/', 'route' => 'rest/clickhouse/clickhouse/total', 'verb' => ['GET', 'OPTIONS']],

            // For tests Block
            ['pattern' => 'api/v1/for-test/test/timestamp', 'route' => 'rest/test/test/timestamp', 'verb' => ['GET']],
            ['pattern' => 'api/v1/for-test/test/query-as', 'route' => 'rest/test/test/test-query-as', 'verb' => ['GET']],

            //General Data
            ['pattern' => 'api/v1/general/get-app', 'route' => 'rest/general_data/general-data/get-app', 'verb' => ['GET', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-period', 'route' => 'rest/general_data/general-data/get-period', 'verb' => ['GET', 'OPTIONS']],
            //Ads Data
            ['pattern' => 'api/v1/general/get-ads-data', 'route' => 'rest/ads/ads/get-ads-data', 'verb' => ['POST', 'OPTIONS']],
            //App Users
            ['pattern' => 'api/v1/general/get-app-users', 'route' => 'rest/app_users/app-users/get-app-users', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-app-users-total', 'route' => 'rest/app_users/app-users/get-app-users-total', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-time-zone-users', 'route' => 'rest/app_users/app-users/get-time-zone-users', 'verb' => ['POST', 'OPTIONS']],
            //Channels Stat
            ['pattern' => 'api/v1/general/get-channels-view-duration', 'route' => 'rest/channels/channels/get-channels-view-duration', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-start-channels', 'route' => 'rest/channels/channels/get-start-channels', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-channels-uniq-users', 'route' => 'rest/channels/channels/get-channels-uniq-users', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-start-app', 'route' => 'rest/channels/channels/get-start-app', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-start-all-app', 'route' => 'rest/channels/channels/get-start-all-app', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/general/get-channels-uniq-users-with-evtp', 'route' => 'rest/channels/channels/get-channels-uniq-users-with-evtp', 'verb' => ['POST', 'OPTIONS']],
            //['pattern' => 'api/v1/general/get-chef-parameter', 'route' => 'rest/channels/channels/get-chef-parameter', 'verb' => ['POST', 'OPTIONS']],
        ], true);
    }
}