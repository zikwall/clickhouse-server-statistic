<?php

namespace app\modules\rest\components;

use Yii;
use yii\web\Response;
use app\modules\rest\helpers\Configuration;

class ResponseHandler
{
    /**
     * @param Response $response
     */
    public function beforeSend ( Response $response ) : void
    {
        $this->setupCorsHeaders($response);
    }

    /**
     * @param Response $response
     */
    public function afterSend( Response $response )
    {
        if (Yii::$app->request->getIsOptions()) {
            return Yii::$app->end(200);
        }
    }

    /**
     * @param Response $response
     */
    final private function setupCorsHeaders ( Response $response ) : void
    {
        /** @var \yii\web\HeaderCollection $headers */
        $headers = $response->headers;

        /** @var array $allowedHeaders */
        $allowedHeaders = Configuration::get('security.cors.allowed.headers');
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Access-Control-Allow-Credentials', true);
        $headers->set('Access-Control-Max-Age', (int) Configuration::get('security.cors.maxAge'));
        $headers->set('Access-Control-Allow-Headers', \implode(', ', $allowedHeaders));
        $headers->set('Access-Control-Allow-Methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS']);
    }
}