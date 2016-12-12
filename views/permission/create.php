<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use adm\rbac2\models\AuthItem;
use adm\rbac2\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */


$this->title = '创建权限';
$this->params['breadcrumbs'][] = ['label' => '权限', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$auth_item = new AuthItem();
$routes = $auth_item->getRoutes();  //所有路由
$use_routes = ArrayHelper::getColumn(AuthItem::getPermission(), 'name');   //已使用的路由
$diff_routes = array_diff($routes, $use_routes);

$group = AuthItem::getAuthItemGroupAll();
AutocompleteAsset::register($this);

?>
<div class="auth-item-create">

    <div class="auth-item-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="bs-example">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="5%">序号</th>
                    <th width="30%">权限组名称</th>
                    <th width="30%">权限名称</th>
                    <th width="35%">权限路由</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $id = 1;
                foreach ($diff_routes as $_item): ?>
                    <tr>
                        <td>
                            <?= $id;?>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" id="authitem-group" class="form-control" name="group[]">
                                <?php if($id !== 1):?>
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="authitem-ditto" class="ditto" name="ditto[]" >
                                        <span>同上</span>
                                    </span>
                                <?php endif;?>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">选择 <span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <?php foreach($group as $g):?>
                                            <li><a class="select_group" href="javascript:void(0);"><?=$g['name']?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" id="authitem-description" class="form-control" name="description[]">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="authitem-join_group" class="join_group" name="join_group[]">
                                    <span> + 组名</span>
                                </span>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" id="authitem-route" class="form-control" name="name[]" value="<?= $_item ?>">
                            <?= $_item ?>
                            <?php $id++?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
