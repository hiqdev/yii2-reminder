<?php
/**
 * Reminder module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-reminder
 * @package   yii2-reminder
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
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
        MomentAsset::class,
    ];
}
