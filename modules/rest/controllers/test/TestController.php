<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Month;
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
    public function actionTimestamp(int $year, int $month)
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
                    $online[$channel] += $count;
                } else {
                    $online[$channel] = $count;
                }
            }
        }

        date_default_timezone_set('Europe/Moscow');
        $monthBegin = strtotime(date('Y-m-01'));
        $dayBegin = mktime(0, 0, 0);
        $hourBegin = strtotime(date('Y-m-d H:0:0'));

        echo date('Y-m-d H:i:s', $hourBegin) . '<br>';

        //echo '<pre>';
        //print_r($online);
        echo 'OK';die;
    }
}