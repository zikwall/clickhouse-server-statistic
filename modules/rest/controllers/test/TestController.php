<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Range;
use app\modules\rest\helpers\DataHelper;
use app\modules\rest\models\Monit;
use app\modules\statistic\models\MonitAppUsers;
use app\modules\statistic\models\MonitChannels;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\From;
use Yii;
use app\modules\statistic\models\MonitData;
use app\modules\statistic\models\MonitAds;

class TestController extends \yii\rest\Controller
{
    public function actionTimestamp()
    {
        $getBy = Yii::$app->request->get('by', null);

        if ($getBy === null) {
            return $this->asJson([
                'message' => 'Empty timestamp'
            ]);
        }

        return $this->asJson([
           'timestamp' => Range::supportInterfaceBy($getBy)->getTimestamp()
        ]);
    }

    public function actionTestQueryAs()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
        $nameChannels = (array) json_decode(file_get_contents('https://pl.iptv2021.com/api/v1/channels?access_token=r0ynhfybabufythekbn'));
        $channelsUniqUsersWithEvtp = [];

        $query = MonitChannels::find()
            ->select([
                'vcid',
                'evtp',
                raw('COUNT(DISTINCT device_id) as ctn')
            ])
            ->from('stat')
            ->where(function (Builder $query) use ($app) {
                if (DataHelper::isAll($app)) {
                    $query->whereIn('app', MonitData::getApp(true));
                } else {
                    $query->where('app', '=', $app);
                }
            })

            ->where('day_begin', '>=', $dayBegin)
            ->where('day_begin', '<=', $dayEnd)
            ->where('adsst', '=', 'NULL')
            ->where('evtp', '!=', 666666)
            ->groupBy(['vcid', 'evtp']);

        $data = MonitChannels::execute($query);

        //Приводим данные в нормальный вид + отсеиваем левые данные

        foreach ($data as $key => $item) {
            if (!isset($nameChannels[$item['vcid']])) {
                continue;
            }

            if (!isset($channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]][0])) {
                $channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]][0] = 0;
            }

            if (!isset($channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]][1])) {
                $channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]][1] = 0;
            }

            $channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]][$data[$key]['evtp']] = $item['ctn'];
            $channelsUniqUsersWithEvtp[$nameChannels[$item['vcid']]]['vcid'] = $item['vcid'];
        }

        echo '<pre>';
        print_r($channelsUniqUsersWithEvtp);
        //print_r(MonitChannels::execute($query));

        //return MonitChannels::execute($query);

        //return MonitAppUsers::getTimeZoneUsers($app, $dayBegin, $dayEnd);
    }
}