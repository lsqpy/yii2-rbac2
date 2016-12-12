<?php

namespace adm\rbac2\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\rbac\Item;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \yii\db\ActiveRecord
{

    const TYPE_GROUP = 3;

    public static function createPermission($group, $name, $description)
    {
        $model = new AuthItem();
        $model->setAttributes([
            "name" => $name,
            "group" => $group,
            "description" => $description,
            "type" => Item::TYPE_PERMISSION,
            "created_at" => time(),
            "updated_at" => time(),
        ]);
        if($model->save()){
            return true;
        }
        return false;
    }

    /**
     * 创建权限组,判断是否已经存在权限组
     * @param $name
     * @return bool|mixed
     */
    public static function createGroup($name)
    {
        $group_name = self::findGroup($name);
        if (!empty($group_name)) return $group_name;
        $model = new AuthItem();
        $model->setAttributes([
            "name" => $name,
            "type" => self::TYPE_GROUP,
            "created_at" => time(),
            "updated_at" => time()
        ]);
        if ($model->save()) {
            return $model->name;
        }
        return false;
    }

    /**
     * 查找权限组是否存在
     * @param $name
     * @return bool|mixed
     */
    public static function findGroup($name)
    {
        $group = AuthItem::find()->where(['type' => self::TYPE_GROUP, 'name' => $name])->one();
        if (!empty($group)) {
            return $group['name'];
        }
        return false;
    }

    /**
     * 获取所有权限
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPermission()
    {
        return self::find()->where(['type' => Item::TYPE_PERMISSION])->asArray()->all();
    }

    /**
     * 获取用户角色
     * @return array
     */
    public static function getAuthRoleAll()
    {
        $auth_role = AuthItem::find()->where(['type' => Item::TYPE_ROLE])->all();
        return ArrayHelper::map($auth_role, 'name', 'name');
    }

    /**
     * 通过分组获取权限
     * @param $group_id
     * @return $this
     */
    public static function getAuthItemAccessByGroup($group)
    {
        return self::find()->where(['group' => $group])->asArray()->all();
    }

    /**
     * 获取所有用户组
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAuthItemGroupAll()
    {
        return AuthItem::find()->where(['type' => self::TYPE_GROUP])->asArray()->all();
    }

    /**
     * Get avaliable and assigned routes
     * @return array
     */
    public function getRoutes()
    {
        return $routes = $this->getAppRoutes();
    }

    /**
     * Get list of application routes
     * @return array
     */
    public function getAppRoutes()
    {
        //if ($module === null) {
        $module = Yii::$app;
        //} elseif (is_string($module)) {
        //    $module = Yii::$app->getModule($module);
        //}
        //$key = [__METHOD__, $module->getUniqueId()];
        //$cache = Configs::instance()->cache;
        //if ($cache === null || ($result = $cache->get($key)) === false) {
        $result = [];
        $this->getRouteRecrusive($module, $result);
        //if ($cache !== null) {
        //    $cache->set($key, $result, Configs::instance()->cacheDuration, new TagDependency([
        //        'tags' => self::CACHE_TAG,
        //    ]));
        //}
        //}

        return $result;
    }

    /**
     * Get route(s) recrusive
     * @param \yii\base\Module $module
     * @param array $result
     */
    protected function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            foreach ($module->getModules() as $id => $child) {

                if (($child = $module->getModule($id)) !== null) {
                    $this->getRouteRecrusive($child, $result);
                }
            }

            foreach ($module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }

            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            $this->getControllerFiles($module, $namespace, '', $result);
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    protected function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file) && preg_match('%^[a-z0-9_/]+$%i', $file . '/')) {
                    $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $baseName = substr(basename($file), 0, -14);
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $baseName));
                    $id = ltrim(str_replace(' ', '-', $name), '-');
                    $className = $namespace . $baseName . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        $this->getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list action of controller
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    protected function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            $this->getActionRoutes($controller, $result);
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    protected function getActionRoutes($controller, &$result)
    {
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                $result[$prefix . $id] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $id = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                    $result[$id] = $id;
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['group', 'description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'group' => 'Group',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%auth_item_child}}', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%auth_item_child}}', ['child' => 'name']);
    }

}
