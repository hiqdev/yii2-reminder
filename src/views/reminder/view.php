<?php

use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use hiqdev\yii2\reminder\grid\ReminderGridView;
use hiqdev\yii2\reminder\menus\ReminderDetailMenu;

$this->title = Yii::t('hiqdev:yii2:reminder', '{0} ID #{1}', [Yii::t('hiqdev:yii2:reminder', ucfirst($model->objectName)), $model->object_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hiqdev:yii2:reminder', 'Reminders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $this->title,
            'icon' => 'fa-bell-o',
            'subTitle' => $model->periodicity_label,
            'menu' => ReminderDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
        ]) ?>
        <?php $box = Box::begin(['bodyOptions' => ['class' => 'no-padding']]); ?>
        <?= ReminderGridView::detailView([
            'boxed' => false,
            'model' => $model,
            'columns' => [
                'description',
                'periodicity',
                'message',
                'next_time',
            ],
        ]); ?>
        <?php $box->end(); ?>
    </div>
</div>
