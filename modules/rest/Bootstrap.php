<?php

namespace app\modules\rest;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            // Auth
            ['pattern' => 'api/v1/auth/login/', 'route' => 'rest/auth/auth/index', 'verb' => ['POST', 'OPTIONS']],
            ['pattern' => 'api/v1/auth/access/', 'route' => 'rest/user/user/access', 'verb' => ['GET', 'OPTIONS']],

            // Data
            ['pattern' => 'api/v1/data/get/', 'route' => 'rest/data/data/index', 'verb' => ['GET']],
            ['pattern' => 'api/v1/asn/', 'route' => 'rest/asn/asn/example', 'verb' => ['GET', 'OPTIONS']],
            ['pattern' => 'api/v1/asn2/', 'route' => 'rest/asn/asn/stat', 'verb' => ['GET', 'OPTIONS']],

            //
            ['pattern' => 'api/v1/clickhouse/total/', 'route' => 'rest/clickhouse/clickhouse/total', 'verb' => ['GET', 'OPTIONS']],

            // For tests Block
            ['pattern' => 'api/v1/for-test/test/timestamp', 'route' => 'rest/test/test/timestamp', 'verb' => ['GET']],
            ['pattern' => 'api/v1/for-test/test/query-as', 'route' => 'rest/test/test/test-query-as', 'verb' => ['GET']],

        ], true);
    }
}