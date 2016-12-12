<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Auth Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                'label' => '权限',
            ],
//            'type',
//            'description:ntext',
//            'rule_name',
//            'data:ntext',
            [
                'attribute' => 'created_at',
                'label' => '创建时间',
                'value' => date("Y-m-d H:i:s",$model->created_at)
            ],
            [
                'attribute' => 'updated_at',
                'label' => '更新时间',
                'value' => date("Y-m-d H:i:s",$model->updated_at)
            ],
        ],
    ]) ?>

</div>
