<?php
namespace app\modules\rest\controllers\user;

use Yii;
use app\modules\rest\components\BaseController;
use app\modules\user\models\UserChannels;

class UserController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionAccess()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return $this->asJson([
            'access' => [
                'permissions' => $this->getUser()->getPermissionList(),
                'groups' => $this->getUser()->getGroupList()
            ]
        ]);
    }
    
    public function actionLinkChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $data = Yii::$app->request->post();

        if (empty($data['channels'])) {
            return $this->asJson([
                'message'   => 'Channels list is empty',
                'result'    => false,
            ]);
        }
        
        $rows = [];
        foreach ($data['channels'] as $channelId) {
            $rows[] = [
                'user_id'       => $this->user->id,
                'channel_id'    => $channelId,
            ];
        }
        
        try {
            Yii::$app->db->createCommand()->batchInsert(UserChannels::tableName(), ['user_id', 'channel_id'], $rows)->execute();
        } catch (\Exception $ex) {
            return $this->asJson([
                'message'   => 'Record existence',
                'return'    => false,
            ]);
        }
        
        return $this->asJson([
            'message'   => 'Success',
            'result'    => true,
        ]);
    }
    
    public function actionUnlinkChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        $channelId = Yii::$app->request->post('channel_id');
        
        if (is_null($channelId)) {
            return $this->asJson([
                'message'   => 'Channel id is null',
                'result'    => false,
            ]);
        }
        
        $userChannel = UserChannels::find()->where(['user_id' => $this->user->id, 'channel_id' => $channelId])->one();
        
        if (is_null($userChannel)) {
            return $this->asJson([
                'message'   => 'Not found',
                'result'    => false,
            ]);
        }
        
        $userChannel->delete();
        
        return $this->asJson([
            'message'   => 'Succesfull',
            'return'    => true,
        ]);
    }
}