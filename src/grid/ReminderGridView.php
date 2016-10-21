<?php

namespace hiqdev\yii2\reminder\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\DataColumn;
use Yii;
use yii\helpers\Html;

class ReminderGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'periodicity' => [
                'filter' => false,
            ],
            'description' => [
                'class' => DataColumn::class,
                'label' => Yii::t('hiqdev/yii2/reminder', 'Description'),
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
                    'class' => 'reminder-next-time-modify'
                ]
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
