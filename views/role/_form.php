<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use adm\rbac2\AutocompleteAsset;
use adm\rbac2\models\AuthItemChild;
/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
AutocompleteAsset::register($this);
$user_access = ArrayHelper::getColumn(AuthItemChild::getUserAccess(Yii::$app->request->get('id')),'child');
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('角色名称') ?>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="10%">权限分组</th>
            <th width="90%">权限</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (\adm\rbac2\models\AuthItem::getAuthItemGroupAll() as $access): ?>
            <tr>
                <td>
                    <input type="checkbox" level="1"><strong><?=$access['name']?></strong>
                </td>
                <td>
                    <?php foreach(\adm\rbac2\models\AuthItem::getAuthItemAccessByGroup($access['name']) as $item):?>
                        <!-- DEBUG模式 -->
                        <?php if(Yii::$app->request->get('debug')):?>
                            <div class="col-md-3">
                                <input type="checkbox" <?php if(in_array($item['name'],$user_access)):?> checked="checked" <?php endif;?>  name="auth_access[]" value="<?=$item['name']?>">
                                <span><?=$item['description']?>(<?=$item['name']?>)</span>
                            </div>
                        <?php else:?>
                            <div class="col-md-2">
                                <input type="checkbox" <?php if(in_array($item['name'],$user_access)):?> checked="checked" <?php endif;?>  name="auth_access[]" value="<?=$item['name']?>">
                                <span><?=$item['description']?></span>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
