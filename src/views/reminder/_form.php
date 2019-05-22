<?php

use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hiqdev\yii2\reminder\models\Reminder;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Reminder $model
 */
$lng = Yii::$app->language;
$this->registerJs("
$('#reminder-from_time').datetimepicker({
    format: 'YYYY-MM-DD HH:mm',
    stepping: 5,
    locale: '{$lng}',
    showClose: true,
    defaultDate: moment().add(1, 'hour').format('YYYY-MM-DD HH:mm')
});
");
?>
<?php $form = ActiveForm::begin([
    'id' => 'reminder-form-' . $model->object_id,
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<?= Html::activeHiddenInput($model, 'object_id') ?>
<?= Html::activeHiddenInput($model, 'offset') ?>
<?php if (!$model->isNewRecord) : ?>
    <?= Html::activeHiddenInput($model, 'id') ?>
<?php endif ?>

<?= $form->field($model, 'type')->radioList([
    Reminder::TYPE_SITE => Yii::t('hiqdev:yii2:reminder', 'To site'),
    Reminder::TYPE_MAIL => Yii::t('hiqdev:yii2:reminder', 'By mail'),
]) ?>

<?= $form->field($model, 'periodicity')->dropDownList($model->getPeriodicityOptions()) ?>

<?= $form->field($model, 'from_time') ?>

<?php
if (Yii::$app->user->can('support')) {
    $model->client_id = Yii::$app->user->identity->id;
    echo $form->field($model, 'client_id')->widget(ClientCombo::class);
}
?>

<?= $form->field($model, 'message')->textarea(['rows' => 4]) ?>

<?= Html::submitButton(Yii::t('hipanel', 'Submit'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
