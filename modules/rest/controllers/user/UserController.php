<?php
namespace app\modules\rest\controllers\user;

use Yii;
use app\modules\rest\components\BaseController;
use app\modules\user\models\UserChannels;
use app\modules\user\models\User;
use app\modules\user\models\UserPermissions;

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
    
    public function actionUserChannelsUpdate()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        $channels = Yii::$app->request->post('channels');
        
        if (sizeof($channels) == 0) {
            return $this->asJson([
                'message' => 'Not found',
                'result' => false,
            ]);
        }
        
        $userChannels = UserChannels::find()->select(['channel_id'])
                ->where(['user_id' => $this->user->id])
                ->asArray()
                ->all();
        $userChannels = \yii\helpers\ArrayHelper::getColumn($userChannels, 'channel_id');

        $deleteList = [];
        $insertList = [];
        
        foreach ($channels as $channel) {
            if (in_array($channel, $userChannels)) {
               $deleteList[] = $channel;
               continue;
            }
            
            $insertList[] = [
                $this->user->id,
                $channel
            ];
        }
        
        if (sizeof($deleteList) > 0) {
            UserChannels::deleteAll([
                'AND',
                'user_id' => $this->user->id,
                ['in', 'channel_id', $deleteList]
                    ]);
        }
        
        if (sizeof($insertList) > 0) {
            Yii::$app->db->createCommand()
                    ->batchInsert(UserChannels::tableName(),['user_id', 'channel_id'],$insertList)
                    ->execute();
        }
        
        return $this->asJson([
            'result' => true
        ]);
    }
    
    public function actionGetUsers()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        $accounts = User::find()->select([
            'id',
            'username',
            'email',
            'confirmed_at',
            'blocked_at',
            'registration_ip',
            'created_at',
            'updated_at'
            ])->asArray()->all();
        
        return $this->asJson($accounts);
    }
    
    public function actionConfirm($id) {
        //$user = $this->finder->findUserById($id);
        $user = User::findOne($id);

        if ($user === null /*|| $userModule->enableConfirmation == false*/) {
            throw new NotFoundHttpException();
        }

        $user->confirmed_at = time();
        $user->save();
               
        $permissionId = 5;
        
        $permissionLink = Yii::createObject([
            'class' => UserPermissions::class,
            'user_id' => $id,
            'permission_id' => $permissionId
        ]);

        if (!$permissionLink->create()) {
            throw new BadRequestHttpException();
        }

        return $this->asJson([
            'id' => $id,
            'confirmed_at' => $user->confirmed_at,
            'message' => "User activated succesfull",
        ]);
    }

    public function actionUnconfirm($id) {
        //$user = $this->finder->findUserById($id);
        $user = User::findOne($id);
        
        if ($user === null /*|| $this->module->enableConfirmation == false*/) {
            throw new NotFoundHttpException();
        }

        $user->confirmed_at = null;
        $user->save();
        
        $userPermissions = new UserPermissions();
        
        if ($userPermissions->terminate($id) == false) {
            throw new NotFoundHttpException();
        }
        
        return $this->asJson([
                    'id' => $id,
                    'confirmed_at' => $user->confirmed_at,
                    'message' => "User disconnect succesfull",
        ]);
    }
    
    public function actionGetUserChannels()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $userChannels = UserChannels::findAll(['user_id' => $this->user->id]);
        $nameChannels = (array) json_decode(file_get_contents('https://pl.iptv2021.com/api/v1/channels?access_token=r0ynhfybabufythekbn'));

        $list = array_map(function($channel) use ($nameChannels) {
            $obj = new \stdClass();
            $obj->id = $channel->channel_id;
            $obj->name = $nameChannels[$channel->channel_id];
            $obj->checked = false;
            
            return $obj;
        }, $userChannels);

        return $this->asJson($list);
    }

}