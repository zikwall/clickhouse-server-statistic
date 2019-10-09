<?php

namespace app\modules\rest;

use Yii;

class Router
{
    private static $routes = [];

    public static function resolveRoute(array $route, array $defaultVerb) : array
    {
        $url = isset($route['pattern']) ? $route['pattern'] : $route['url'];
        $path = isset($route['route']) ? $route['route'] : $route['path'];
        $verb = $route['verb'] ?? [];

        if (empty($verb) && !empty($defaultVerb)) {
            $verb = array_merge($verb, $defaultVerb);
        }

        $useOptions = isset($route['useOptions']) ? $route['useOptions'] : true;

        return [$url, $path, $verb, $useOptions];
    }

    public static function addGroupUrl(string $group, array $routes = [], array $defaultVerb = ['GET'])
    {
        foreach ($routes as $route) {
            [$url, $path, $verb, $useOptions] = self::resolveRoute($route, $defaultVerb);
            $url = sprintf('%s/%s', $group, $url);

            self::addRoute($url, $path, $verb, $useOptions);
        }
    }

    public static function addTEST($url, $action, $verb = [],bool $useOptions = true)
    {
        $urlf = 'api/v1/for-test/test';
        $path = 'rest/test/test';

        self::addRoute(
            sprintf('%s/%s', $urlf, $url),
            sprintf('%s/%s', $path, $action) ,
            $verb, $useOptions
        );
    }

    public static function addGET($url, $path, bool $useOptions = true)
    {
        self::addRoute($url, $path, ['GET'], $useOptions);
    }

    public static function addPOST($url, $path, bool $useOptions = true)
    {
        self::addRoute($url, $path, ['POST'], $useOptions);
    }

    public static function addPOSTS(array $routes)
    {
        foreach ($routes as $route) {
            self::addPOST($route['url'], $route['path'], $route['useOptions']);
        }
    }

    public static function addRoute(string $url, string $path, array $verbs = [], bool $useOptions = true)
    {
        if ($url == '' || $path == '') {
            return;
        }

        if ($useOptions) {
            $verbs = array_merge($verbs, ['OPTIONS']);
        }

        self::$routes[] = [
            'pattern'   => $url,
            'route'     => $path,
            'verb'      => $verbs
        ];
    }

    public static function addRoutes(array $routes = [], $append = true)
    {
        foreach ($routes as $route) {
            [$url, $path, $verb, $useOptions] = self::resolveRoute($route, ['GET']);
            self::addRoute($url, $path, $verb, $useOptions);
        }
    }

    public static function init()
    {
        Yii::$app->urlManager->addRules(self::$routes, true);
    }
}