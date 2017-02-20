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

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use Yii;
use yii\helpers\Html;

class ReminderGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'periodicity' => [
                'value' => function ($model) {
                    return Yii::t('hiqdev:yii2:reminder', $model->periodicity_label);
                },
                'filter' => false,
            ],
            'description' => [
                'label' => Yii::t('hiqdev:yii2:reminder', 'Description'),
                'value' => function ($model) {
                    return Html::a($model->objectLabel, $model->objectLink);
                },
                'format' => 'html',
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
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
