<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\reminder;

use omnilight\assets\MomentAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ReminderTopAsset extends AssetBundle
{
    public $sourcePath = '@hiqdev/yii2/reminder/assets';

    public $css = [
        'css/reminderTop.css',
    ];

    public $js = [
        'js/reminderTop.js',
    ];

    public $depends = [
        JqueryAsset::class,
        MomentAsset::class
    ];
}
