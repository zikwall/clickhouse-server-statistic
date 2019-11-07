<?php

namespace app\commands;

use app\modules\statistic\models\MonitLoadChannels;
use yii\console\Controller;

class CronController extends Controller
{
    public function actionChannelLoads()
    {
        MonitLoadChannels::saveLoadChannels();
    }
}