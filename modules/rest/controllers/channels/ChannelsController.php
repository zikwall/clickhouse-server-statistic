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

        /*$request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');*/
        //$eventType = $request->post('eventType');

        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
        $data = MonitChannels::getChannelsViewDuration($app, $dayBegin, $dayEnd);

        return $this->asJson([
            'channelsViewDuration' => $data
        ]);
    }

    public function actionGetStartChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
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

        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
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

        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;
        $data = MonitChannels::getStartApp($app, $dayBegin, $dayEnd);

        return $this->asJson([
            'startApp' => $data
        ]);
    }
}