<?php
namespace rsr\yii2\checkDeps;

use yii\web\AssetBundle;

/**
 *
 * @author Reza Saberi Rad
 */
class Asset extends AssetBundle
{

    public $sourcePath = '@vendor/rsr/yii2-check-deps/assets';

    public $css = [];

    public $js = [];

    public $depends = [
        'yii\web\YiiAsset'
    ];
}