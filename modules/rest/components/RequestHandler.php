<?php

namespace app\modules\rest\components;

use Yii;

class RequestHandler
{
    /**
     * @throws \yii\base\ExitException
     */
    public function beforeSend () : void
    {
        /** @var string $pathInfo */
        $pathInfo = Yii::$app->request->pathInfo;

        // fixed: UrlRule Problem with trailing slash (/)
        if (!empty($pathInfo) && '/' === \substr($pathInfo, -1)) {
            Yii::$app->response->redirect('/' . \rtrim($pathInfo, '/'))->send();
            exit;
        }

        // No output for OPTIONS
        if ('OPTIONS' === Yii::$app->getRequest()->getMethod()) {
            Yii::$app->end ();
            exit;
        }
    }

    public function afterSend () : void {}
}