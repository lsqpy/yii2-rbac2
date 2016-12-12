<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建权限', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'group',
                'label' => '权限分组名称',
            ],
            [
                'attribute' => 'description',
                'label' => '权限名称',
            ],
            [
                'attribute' => 'name',
                'label' => '权限地址',
            ],
//            'type',

//            'rule_name',
//            'data:ntext',
             [
                 'attribute' => 'created_at',
                 'label' => '创建时间',
                 'value' => function($model){
                     return date("Y-m-d",$model->created_at);
                 }
             ],
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
