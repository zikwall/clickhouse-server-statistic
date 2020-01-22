<?php
namespace app\modules\rest\controllers\user;

use Yii;
use app\modules\rest\components\BaseController;
use app\modules\user\models\UserChannels;
use app\modules\user\models\User;
use app\modules\user\models\UserPermissions;
use app\modules\user\models\Permissions;

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
        $userId = Yii::$app->request->post('user');

        if (sizeof($channels) == 0) {
            return $this->asJson([
                'message' => 'Not found',
                'result' => false,
            ]);
        }
        
        $userChannels = UserChannels::find()->select(['channel_id'])
                ->where(['user_id' => $userId])
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
                $userId,
                $channel
            ];
        }
        
        if (sizeof($deleteList) > 0) {
            UserChannels::deleteAll([
                'AND',
                'user_id' => $userId,
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
            'description',
            'created_at',
            'updated_at'
            ])->asArray()->all();
        
        return $this->asJson($accounts);
    }
    
    public function actionConfirm($id) {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $permission = Permissions::findOne([
                    'permission' => 'canViewPrivate'
        ]);

        if ($permission === null) {
            return [
                'message' => 'Not found permission'
            ];
        }
        
        $user = User::findOne($id);
        
        if ($user === null) {
            return [
                'message' => 'Not found user'
            ];
        }

        $user->confirmed_at = time();
        $user->save();
                
        $permissionLink = Yii::createObject([
            'class' => UserPermissions::class,
            'user_id' => $id,
            'permission_id' => $permission->id,
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

    // TODO: сделать по нормальному
    public function actionUnconfirm($id) {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $user = User::findOne($id);
        
        if ($user === null) {
            return [
                'message' => 'User not found'
            ];
        }

        if (in_array('canViewDashboard', $user->getPermissionList())) {
            return [
                'message' => 'No permissions for delete'
            ];
        }
        
        $user->confirmed_at = null;
        $user->save();
        
        $userPermissions = new UserPermissions();
        
        if ($userPermissions->terminate($id) == false) {
            return [
                'message' => 'Not found user persmissions'
            ];
        }
        
        return $this->asJson([
                    'id' => $id,
                    'confirmed_at' => $user->confirmed_at,
                    'message' => "User disconnect succesfull",
        ]);
    }
    
    /**
     * Список телеканалов пользователя
     * 
     * @param integer $id
     * @return array
     */
    public function actionGetUserChannels($id = null)
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        //Если не передали id, то запрос от авторизированного пользователя
        //Знаю что говнокод. Но вынужден пока так. Переделаю потом.
        if (is_null($id)) {
            $id = $this->user->id;
        }

        $userChannels = UserChannels::findAll(['user_id' => $id]);
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

    public function actionCreateUser()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $model = new User();
        $model->setScenario('createUserByManager');
        
        if (!$model->load(Yii::$app->request->post(), '')) {
            throw new \yii\web\ServerErrorHttpException;
        }
        
        if (!$model->createByManager()) {
            throw new \yii\web\BadRequestHttpException;
        }

        return $this->asJson([
            'status' => true
        ]);
    }

}