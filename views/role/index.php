<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建角色', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'name',
                'label'=>'角色名称',
            ],
//            'type',
            [
                'attribute'=>'description',
                'label'=>'角色人员',
                'value'=>function($model){
                    $user = adm\rbac2\models\AuthAssignment::getUserInfoByRole($model->name);
                    return implode(',',\yii\helpers\ArrayHelper::getColumn($user,'username'));
                }
            ],
//            'rule_name',
//            'data:ntext',
//             'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
