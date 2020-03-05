<?php
namespace app\modules\rest\controllers\ads;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitAds;
use app\modules\statistic\models\MonitData;
use Yii;

class AdsController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetAdsData()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        //$eventType = $request->post('eventType');
        $data = MonitAds::getData($app, $dayBegin, $dayEnd/*, $eventType*/)->rows;
        $arrAdsId = MonitData::getAdsId();

        foreach($data as $k => $v) {
            if (!in_array($v['adsid'], $arrAdsId)) {
                unset($data[$k]);
            }
        }


        return $this->asJson([
            'adsData' => array_values($data)
        ]);
    }
    
    public function actionGetAdsDataOfPartnerChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
                return true;
        }

        $request = Yii::$app->request;
        $userChannels = $request->post('userChannels');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        
        $userChannelsFormatedList = array_column($userChannels, 'name', 'id');
        $userChannelsIds = array_keys($userChannelsFormatedList);
        
        $forapp = $userChannels[0]['label'];

        $data = MonitAds::getDataOfPartnerChannels($userChannelsIds, $dayBegin, $dayEnd, $forapp)->rows;
        $arrAdsId = MonitData::getAdsId();

        foreach($data as $k => $v) {
            if (!in_array($v['adsid'], $arrAdsId)) {
                unset($data[$k]);
            } else {
                $data[$k]['name'] = $userChannelsFormatedList[$v['vcid']];
            }
        }


        return $this->asJson([
            'adsData' => array_values($data)
        ]);

    }
}