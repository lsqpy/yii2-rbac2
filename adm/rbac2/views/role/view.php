<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定你要删除这个项目吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'label' => '权限分组名称',
            ],
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
