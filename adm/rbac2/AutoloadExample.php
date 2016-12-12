<?php

namespace adm\rbac2;

use adm\rbac2\models\AuthAssignment;
use adm\rbac2\models\AuthItem;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is just an example.
 */
class AutoloadMenu extends \yii\base\Widget
{
    public $item;

    public function run()
    {
        $this->registerPlugin();
    }

    public function registerPlugin(){
        //不需要过滤的用户
        $user = Yii::$app->getBehavior('access')->allowUser;
        if(in_array(Yii::$app->user->identity->username,$user)){
            return $this->item;
        }

        //获取当前用户角色
        $role = AuthAssignment::find()->where(['user_id'=>Yii::$app->user->id])->all();
        $access = AuthItem::find()->where(['in','parent',ArrayHelper::getColumn($role,'item_name')])->all();
    }
}
