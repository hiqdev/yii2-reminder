<?php
/**
 * Reminder module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-reminder
 * @package   yii2-reminder
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\reminder\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hiqdev\yii2\menus\grid\MenuColumn;
use hiqdev\yii2\reminder\menus\ReminderActionsMenu;
use Yii;
use yii\helpers\Html;

class ReminderGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'periodicity' => [
                'value' => function ($model) {
                    return Yii::t('hiqdev:yii2:reminder', $model->periodicity);
                },
                'filter' => false,
            ],
            'type' => [
                'class' => RefColumn::class,
                'attribute' => 'type_label',
                'filterAttribute' => 'type_in',
                'gtype' => 'type,reminder',
                'i18nDictionary' => 'hiqdev:yii2:reminder',
            ],
            'description' => [
                'label' => Yii::t('hiqdev:yii2:reminder', 'Description'),
                'value' => function ($model) {
                    return Html::a($model->objectLabel, $model->objectLink);
                },
                'format' => 'html',
            ],
            'object' => [
                'label' => Yii::t('hiqdev:yii2:reminder', 'Object'),
                'attribute' => 'objectLabel',
                'filter' => false,
            ],
            'message' => [
                'filter' => false,
            ],
            'next_time' => [
                'filter' => false,
                'contentOptions' => [
                    'class' => 'reminder-next-time-modify',
                ],
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => ReminderActionsMenu::class,
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'width:1%; white-space:nowrap;',
                ],
            ],
        ]);
    }
}
