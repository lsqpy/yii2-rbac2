<?php

namespace adm\rbac2\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\User;

/**
 * This is the model class for table "{{%auth_assignment}}".
 *
 * @property integer $user_id
 * @property integer $role_id
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户ID',
            'role_id' => '角色ID',
        ];
    }

    public static function getUserInfoByRole($role)
    {
        if(empty($role)) return [];
        $role_info = self::getRoleUser($role);

        $user = Yii::$app->getUser()->identityClass;
        $user_model = new $user;
        return $user_model->find()->where(['in','id',ArrayHelper::getColumn($role_info,'user_id')])->asArray()->all();

    }

    /**
     * 获取用户角色
     * @param $user_id
     */
    public static function getUserRole($user_id)
    {
        return self::find()->where(['user_id' => $user_id])->asArray()->all();
    }

    /**
     * 通过角色获取用户ID
     * @param $role
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRoleUser($role)
    {
        return self::find()->where(['item_name'=>$role])->asArray()->all();
    }

    /**
     * 更新用户分配角色
     * @param $user_id
     * @param $role_name
     */
    public static function updateUserRole($user_id, $role_name)
    {
        self::deleteUserRole($user_id);
        self::createUserRole($user_id, $role_name);
    }

    /**
     * 创建用户分配角色
     * @param $user_id
     * @param array $role_name
     */
    public static function createUserRole($user_id, $role_name = [])
    {
        $auth = Yii::$app->authManager;
        if (!empty($role_name)) {
            //更新用户角色
            foreach ($role_name as $role) {
                $reader = $auth->createRole($role);
                $auth->assign($reader, $user_id);
            }
        }
    }

    /**
     * 删除用户分配角色
     * @param $user_id
     */
    public static function deleteUserRole($user_id)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($user_id);
    }
}
