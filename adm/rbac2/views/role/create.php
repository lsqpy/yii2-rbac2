<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = '创建角色';
$this->params['breadcrumbs'][] = ['label' => '角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
