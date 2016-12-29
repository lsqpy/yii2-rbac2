rbac
====
rbac

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vendor/rbac2 "*"
```

or add

```
"vendor/rbac2": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \adm\rbac2\AutoloadExample::widget(); ?>```config
-----
```
'modules' => [       
	'rbac' => [
    	'class' => 'adm\rbac2\Module',
  	],
],

```
```
'as access' => [
	'class' => 'adm\rbac2\components\AccessControl',
	'allowActions' => [
		'site/*'
	],
	'allowUser' => [
		'admin','lsqpy'
	]
],

```
```
'aliases' => [
	'@adm/rbac2' => '@vendor/adm/rbac2',
],
```
