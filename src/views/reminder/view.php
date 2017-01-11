<?php

use hipanel\widgets\Box;
use hiqdev\yii2\reminder\grid\ReminderGridView;

$this->title = Yii::t('hiqdev:yii2:reminder', '{0} ID #{1}', [Yii::t('hiqdev:yii2:reminder', ucfirst($model->objectName)), $model->object_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hiqdev:yii2:reminder', 'Reminders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-12">
        <?php
        $box = Box::begin();
        echo ReminderGridView::detailView([
            'boxed' => false,
            'model' => $model,
            'columns' => [
                'description',
                'periodicity',
                'message',
                'next_time',
            ],
        ]);
        $box->end();
        ?>
    </div>
</div>
