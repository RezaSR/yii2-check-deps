# yii2-check-deps
Check for new migrations/dependencies of Yii 2 application when in development mode and apply them if user asked to.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --dev rsr/yii2-check-deps
```

or add

```
"rsr/yii2-check-deps" : "*"
```

to the require-dev section of your `composer.json` file.

Usage
----

Add the following to yii2 config/web.php file:

```
...
if (YII_ENV_DEV) {
    ... 
    $config['bootstrap'][] = 'checkDeps';
    $config['components']['checkDeps'] = [
        'class' => rsr\yii2\checkDeps\CheckDeps::class
    ];
    ...
}
...
return $config;
```
