<?php

namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;

class MonitLoadChannels extends CHBaseModel
{
    public static function saveLoadChannels()
    {
        $data = [];
        $online = [];

        foreach (MonitData::BROADCASTERS as $broadcastUrl) {
            $answer = @file_get_contents($broadcastUrl);

            if (!$answer) {
                continue;
            }

            $data[] = json_decode(file_get_contents($broadcastUrl), true)['CHANNELS'];
        }

        foreach ($data as $broadcast) {
            foreach ($broadcast as $channel => $count) {
                if (isset($online[$channel])) {
                    $online[$channel] += (int) $count;
                } else {
                    $online[$channel] = (int) $count;
                }
            }
        }

        if (empty($online)) {
            return;
        }

        date_default_timezone_set('Europe/Moscow');
        $monthBegin = strtotime(date('Y-m-01'));
        $dayBegin = mktime(0, 0, 0);
        $hourBegin = strtotime(date('Y-m-d H:0:0'));

        $insertedRows = [];

        foreach ($online as $url => $count) {
            $insertedRows[] = [
                'month_begin'  => $monthBegin,
                'day_begin'    => $dayBegin,
                'hour_begin'   => $hourBegin,
                'url_protocol' => $url,
                'count'        => (int) $count
            ];
        };

        if (empty($insertedRows)) {
            return;
        }

        self::getBuilder()->table('channel_loads')->insert($insertedRows);
    }
}