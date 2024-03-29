<?php

use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\yii2\reminder\grid\ReminderGridView;
use yii\base\Model;

/**
 * @var Model $model
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('hiqdev:yii2:reminder', 'Reminders');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

$representation = Yii::$app->request->get('representation');
?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

<?php $page->setSearchFormData() ?>

<?php $page->beginContent('bulk-actions') ?>
    <?= $page->renderBulkDeleteButton('/reminder/reminder/delete') ?>
<?php $page->endContent() ?>

<?php $page->beginContent('table') ?>
    <?php $page->beginBulkForm() ?>
        <?= ReminderGridView::widget([
            'boxed' => false,
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered table-condensed',
            ],
            'columns' => [
                'checkbox',
                'actions',
                'periodicity',
                'object',
                'type',
                'next_time',
            ],
        ]) ?>
    <?php $page->endBulkForm() ?>
<?php $page->endContent() ?>
<?php $page->end() ?>
