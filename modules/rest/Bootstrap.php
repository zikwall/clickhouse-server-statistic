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

        ], true);
    }
}