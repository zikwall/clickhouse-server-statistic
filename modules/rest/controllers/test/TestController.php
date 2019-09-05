<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Range;
use app\modules\rest\models\Monit;
use app\modules\statistic\models\MonitChannels;
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

        $app = "all";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
        $data = MonitChannels::getChannelsViewDuration($app, $dayBegin, $dayEnd);
        $channelsViewDuration = [];
        $nameChannels = (array) json_decode(file_get_contents('https://limehd.tv/api/v1/channels?access_token=r0ynhfybabufythekbn'));

        foreach($data->rows as $key => $value) {
            if (!isset($nameChannels[$value['vcid']])){
                continue;
            }

            $channelsViewDuration[$nameChannels[$value['vcid']]]['vcid'] = $value['vcid'];
            $channelsViewDuration[$nameChannels[$value['vcid']]]['name'] = $nameChannels[$value['vcid']];
            $channelsViewDuration[$nameChannels[$value['vcid']]]['online'] = 0;
            $channelsViewDuration[$nameChannels[$value['vcid']]]['archive'] = 0;

            foreach($value['groupData'] as $key1 => $value1) {
                if ($value1[0] == 0) {
                    $channelsViewDuration[$nameChannels[$value['vcid']]]['online'] = sprintf('%02d:%02d:%02d', ($value1[1]*30)/3600, (($value1[1]*30)%3600)/60, (($value1[1]*30)%3600)%60);
                }

                if ($value1[0] == 1) {
                    $channelsViewDuration[$nameChannels[$value['vcid']]]['archive'] = sprintf('%02d:%02d:%02d', ($value1[2]*30)/3600, (($value1[2]*30)%3600)/60, (($value1[2]*30)%3600)%60);
                }
            }
        }

        return $this->asJson([
            'channelsViewDuration' => $channelsViewDuration
        ]);
    }
}