<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '更新权限: ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Auth Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
