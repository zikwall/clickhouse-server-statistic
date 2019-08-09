<?php

namespace app\modules\user\models;

use app\modules\core\components\base\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property integer $community_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Group extends ActiveRecord
{
    const SCENARIO_EDIT = 'edit';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('UserModule.models_User', 'Name'),
            'description' => Yii::t('UserModule.models_User', 'Description'),
        ];
    }

    public static function getAdminGroup() : Group
    {
        return Group::findOne(['is_admin_group' => 1]);
    }

    public static function getAdminGroupId() : int
    {
        $adminGroupId = Yii::$app->getModule('user')->settings->get('group.adminGroupId');

        if ($adminGroupId == null) {
            $adminGroupId = Group::getAdminGroup()->id;
            Yii::$app->getModule('user')->settings->set('group.adminGroupId', $adminGroupId);
        }

        return $adminGroupId;
    }

    /**
     * @return ActiveQuery менеджеры групп User[]
     */
    public function getManager()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('groupUsers',
            function (ActiveQuery $query) {
                $query->where(['is_group_manager' => '1']);
        });
    }

    public function hasManager() : bool
    {
        return $this->getManager()->count() > 0;
    }

    /**
     * @param int|User $user
     * @return GroupUser
     */
    public function getGroupUser($user) : GroupUser
    {
        $userId = ($user instanceof User) ? $user->id : $user;
        return GroupUser::findOne(['user_id' => $userId, 'group_id' => $this->id]);
    }

    public function getGroupUsers()
    {
        return $this->hasMany(GroupUser::class, ['group_id' => 'id']);
    }

    public function getUsers() : ActiveQuery
    {
        $query = User::find();
        $query->leftJoin('{{%group_user}}', 'group_user.user_id=user.id AND group_user.group_id=:groupId', [
            ':groupId' => $this->id
        ]);
        $query->andWhere(['IS NOT', 'group_user.id', new \yii\db\Expression('NULL')]);
        $query->multiple = true;
        return $query;
    }

    public function hasUsers() : bool
    {
        return $this->getUsers()->count() > 0;
    }

    public function isManager($user) : bool
    {
        $userId = ($user instanceof User) ? $user->id : $user;
        return $this->getGroupUsers()->where(['user_id' => $userId, 'is_group_manager' => true])->count() > 0;
    }

    public function isMember($user) : bool
    {
        return $this->getGroupUser($user) != null;
    }

    public function addUser($user, $isManager = false) : void
    {
        if ($this->isMember($user)) {
            return;
        }

        $userId = ($user instanceof User) ? $user->id : $user;

        $newGroupUser = new GroupUser();
        $newGroupUser->user_id = $userId;
        $newGroupUser->group_id = $this->id;
        $newGroupUser->is_group_manager = $isManager;
        $newGroupUser->save();
    }

    public function removeUser($user) : void
    {
        $groupUser = $this->getGroupUser($user);
        if ($groupUser != null) {
            $groupUser->delete();
        }
    }
}
