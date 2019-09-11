<?php
namespace app\modules\rest\controllers\channels;

use app\modules\rest\components\BaseController;
use Yii;
use app\modules\statistic\models\MonitChannels;

class ChannelsController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetChannelsViewDuration()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $data = MonitChannels::getChannelsViewDuration($app, $dayBegin, $dayEnd);
        $nameChannels = (array) json_decode(file_get_contents('https://limehd.tv/api/v1/channels?access_token=r0ynhfybabufythekbn'));

        /**
         * Приведение данных к нормальному виду и подстановка названий каналов + отсев мусора
         */

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
                    //$channelsViewDuration[$nameChannels[$value['vcid']]]['online'] = sprintf('%02d:%02d:%02d', ($value1[1]*30)/3600, (($value1[1]*30)%3600)/60, (($value1[1]*30)%3600)%60);
                    $channelsViewDuration[$nameChannels[$value['vcid']]]['online'] = $value1[1];
                }

                if ($value1[0] == 1) {
                    //$channelsViewDuration[$nameChannels[$value['vcid']]]['archive'] = sprintf('%02d:%02d:%02d', ($value1[2]*30)/3600, (($value1[2]*30)%3600)/60, (($value1[2]*30)%3600)%60);
                    $channelsViewDuration[$nameChannels[$value['vcid']]]['archive'] = $value1[2];
                }
            }
        }

        return $this->asJson([
            'channelsViewDuration' => $channelsViewDuration
        ]);
    }

    public function actionGetStartChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $data = MonitChannels::getStartChannels($app, $dayBegin, $dayEnd);

        return $this->asJson([
            'startChannels' => $data
        ]);
    }

    public function actionGetChannelsUniqUsers()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $data = MonitChannels::getChannelsUniqUsers($app, $dayBegin, $dayEnd);

        return $this->asJson([
            'channelsUniqUsers' => $data
        ]);
    }

    public function actionGetStartApp()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $data = MonitChannels::getStartApp($app, $dayBegin, $dayEnd);

        return $this->asJson([
            'startApp' => $data
        ]);
    }
}