<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use adm\rbac2\AutocompleteAsset;
use kartik\select2\Select2;
use adm\rbac2\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
$auth_item = new AuthItem();
$routes = $auth_item->getRoutes();  //所有路由
$use_routes = ArrayHelper::getColumn(AuthItem::getPermission(), 'name');   //已使用的路由
$diff_routes = array_diff($routes, $use_routes);
AutocompleteAsset::addScript($this, [Url::base() . '/assets/auth_item_access.js',], [AutocompleteAsset::className(), 'depends' => 'adm\rbac2\AutocompleteAsset'])
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group')->widget(Select2::className(), [
        'data' => ArrayHelper::map(AuthItem::getAuthItemGroupAll(), 'name', 'name'),
        'options' => ['placeholder' => '请选择权限组'],
        'pluginOptions' => ['allowClear' => true],
        'hideSearch' => true,
    ])->label('权限组');
    ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label('名称') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('权限') ?>

    <div class="form-group field-authitemaccess-auth_item_select has-success">
        <label class="control-label" for="authitemaccess-auth_item_select">路由</label>
        <select multiple size="20" class="form-control list" id="auth_item_access" data-target="avaliable">
            <?php foreach ($diff_routes as $_item): ?>
                <option value="<?= $_item ?>"><?= $_item ?></option>
            <?php endforeach; ?>
        </select>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
