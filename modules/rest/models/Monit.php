<?php

namespace app\modules\rest\models;

use app\modules\clickhouse\models\CHBaseModel;

class Monit extends CHBaseModel
{
    public static function getExample()
    {
        // get count users by channels
        $query = self::find()->select(['vcid', raw('count(*) as ctn')])
            ->from('stat')
            ->where('created_at', '>=', mktime(0,0,0))
            ->where('app', 'com.infolink.limeiptv')
            ->groupBy('vcid');

        return self::execute($query);
    }
}