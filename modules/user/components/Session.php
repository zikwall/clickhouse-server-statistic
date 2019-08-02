<?php

namespace app\modules\user\components;

use Yii;
use yii\web\DbSession;
use yii\web\ErrorHandler;
use yii\db\Query;
use yii\db\Expression;
use app\modules\user\models\Session as SessionModel;

/**
 * @inheritdoc
 */
class Session extends DbSession
{
    public static function getOnlineUsers()
    {
        return \app\modules\user\models\User::find()
            ->leftJoin(SessionModel::tableName(), SessionModel::tableName().'.user_id=user.id')
            ->andWhere(['IS NOT', SessionModel::tableName().'.user_id', new Expression('NULL')])
            ->andWhere(['>', SessionModel::tableName().'.expire', time()]);
    }

    /**
     * @inheritdoc
     */
    public function writeSession($id, $data)
    {
        // exception must be caught in session write handler
        // http://us.php.net/manual/en/function.session-set-save-handler.php
        try {
            $userId = new Expression('NULL');
            if (!Yii::$app->user->getIsGuest()) {
                $userId = Yii::$app->user->id;
            }

            $expire = time() + $this->getTimeout();
            $query = new Query;
            $exists = $query->select(['id'])
                    ->from($this->sessionTable)
                    ->where(['id' => $id])
                    ->createCommand($this->db)
                    ->queryScalar();
            if ($exists === false) {
                $this->db->createCommand()
                        ->insert($this->sessionTable, [
                            'id' => $id,
                            'data' => $data,
                            'expire' => $expire,
                            'user_id' => $userId,
                        ])->execute();
            } else {
                $this->db->createCommand()
                        ->update($this->sessionTable, ['data' => $data, 'expire' => $expire, 'user_id' => $userId], ['id' => $id])
                        ->execute();
            }
        } catch (\Exception $e) {
            $exception = ErrorHandler::convertExceptionToString($e);
            // its too late to use Yii logging here
            error_log($exception);
            echo $exception;

            return false;
        }

        return true;
    }

}
