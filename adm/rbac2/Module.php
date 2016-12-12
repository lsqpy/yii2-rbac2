<?php

namespace adm\rbac2;

/**
 * rbac2 module definition class
 * 'modules' => [
 *      'rbac2' => [
 *          'class' => 'backend\rbac2\Module',
 *      ],
 * ],
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'adm\rbac2\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
