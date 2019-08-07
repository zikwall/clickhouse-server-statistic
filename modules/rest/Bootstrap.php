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
            ['pattern' => 'api/v1/data/get/', 'route' => 'rest/data/data/index', 'verb' => ['GET']],
            ['pattern' => 'api/v1/auth/permissions/', 'route' => 'rest/user/user/permissions', 'verb' => ['GET', 'OPTIONS']],
        ], true);
    }
}