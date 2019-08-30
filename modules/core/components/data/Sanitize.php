<?php
namespace app\modules\core\components\data;

class Sanitize
{
    final public static function cleanup($sanitizeData = [], $match = [])
    {
        $match = array_merge([
            'replace' => [], 'merge' => [], 'remove' => []
        ], $match);

        [
            'replace' => $replace,
            'remove' => $remove,
            'merge' => $merge
        ] = $match;

        $sinitizedArray = [];
        $count = count($sanitizeData);

        for ($c = 0; $c <= $count; $c++) {

        }
    }

    protected function merge($toMerged, $totalMerged)
    {
        foreach ($toMerged as $forMerge => $mergeValue) {
            if (!is_numeric($mergeValue)) {
                continue;
            }

            $totalMerged = !isset($totalMerged[$forMerge])
                ? $mergeValue
                : $totalMerged[$forMerge] += $mergeValue;
        }
    }
}

$dataAdsst = [
    ['comlete',  1232,],
    ['reperst',  2342],
    ['request',  214],
    ['complete', 3434],
    ['showes',   45]
];

$dataAdstpAdsst = [
    'preroll_yandex' => ['comlete',  1232],
    'cache_yandex'   => ['reperst',  2342],
    'preroll_ima'    => ['request',  214],
    'yandex'         => ['complete', 3434],
    'cahce_google'   => ['showes',   45],
    'ima'            => ['answer',   394]
];

$wrongData = [
    'replace' => [
        'comlete' => 'complete',
        'reperst' => 'request',
        'showes'  => 'show'
    ],
    'merge' => [
        'preroll_yandex' => 'yandex',
        'cache_yandex' => 'yandex',
        'preroll_ima' => 'ima',
        'cahce_google' => 'google'
    ],
    'removeValues' => [
        '8', '2', '5', '1'
    ]
];

Sanitize::cleanup();