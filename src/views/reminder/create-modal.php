<?php
/**
 * @var \hipanel\models\Reminder
 */
// Set client offset
$this->registerJs('$("#reminder-offset").val(moment().utcOffset())');
?>

<?= $this->render('_form', compact('model')); ?>
