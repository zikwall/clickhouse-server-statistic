<?php
namespace app\modules\rest\helpers;

class Configuration
{
    final public static function current() : array
    {
        return [
            'uri' => [
                'baseUrl' => 'http://localhost:8080'
            ],
            'security' => [
                'cors' => [
                    'allowed' => [
                        'origins' => ['*'],
                        'headers' => [
                            "Accept", "Origin", "X-Auth-Token",
                            "Content-Type", "Authorization", "X-Requested-With",
                            "Accept-Language", "Last-Event-ID", "Accept-Language",
                            "Cookie", "Content-Length", "WWW-Authenticate", "X-XSRF-TOKEN",
                            "withcredentials", "x-forwarded-for", "x-real-ip",
                            "x-customheader", "user-agent", "keep-alive", "host",
                            "connection", "upgrade", "dnt", "if-modified-since", "cache-control"
                        ]
                    ],
                    'maxAge' => 86400
                ],
            ]
        ];
    }

    public static function exists($array, $key) : bool
    {
        return array_key_exists($key, $array);
    }

    public static function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return self::current();
        }

        if (self::exists(self::current(), $key)) {
            return self::current()[$key];
        }

        if (strpos($key, '.') === false) {
            return $default;
        }

        $items = self::current();

        foreach (explode('.', $key) as $segment) {
            if (!is_array($items) || !self::exists($items, $segment)) {
                return $default;
            }

            $items = &$items[$segment];
        }

        return $items;
    }
}