<?php

namespace app\modules\rest;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \app\modules\core\components\base\Module
{
    public $enabledForAllUsers = false;
    public $disabledUsers = [];
    public $jwtKey = 'shwjskwskwsluhrnfrlkfeHWJSKuwmswkUWKwnskiwswswlkmdc';
    public $jwtExpire = 420;
    public $enableBasicAuth = false;
}