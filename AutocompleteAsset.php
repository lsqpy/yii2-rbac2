<?php

namespace adm\rbac2;

use yii\web\AssetBundle;

/**
 * AutocompleteAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AutocompleteAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/adm/rbac2/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'auth.css'
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'auth.js'
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
