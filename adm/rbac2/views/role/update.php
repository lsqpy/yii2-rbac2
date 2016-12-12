<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '更新角色: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = '更新角色';
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
