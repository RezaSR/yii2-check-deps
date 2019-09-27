<?php
namespace rsr\yii2\checkDeps;

use yii\web\AssetBundle;

/**
 *
 * @author Reza Saberi Rad
 */
class Asset extends AssetBundle
{

    public $sourcePath = '@rsrCheckDepsRoot/assets';

    public $css = [
        'css/process.css'
    ];

    public $js = [
        'js/process.js'
    ];

    public $depends = [
        'yii\web\YiiAsset'
    ];
}