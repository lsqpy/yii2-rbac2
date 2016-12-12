<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use core\models\AuthItem;
use backend\assets\AppAsset;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
$auth_item = new AuthItem();
$routes = $auth_item->getRoutes();
AppAsset::addScript($this,[Url::base() . '/js/auth_item_access.js',], [AppAsset::className(), 'depends' => 'backend\assets\AppAsset'])
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('名称') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
