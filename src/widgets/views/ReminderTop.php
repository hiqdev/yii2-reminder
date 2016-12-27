<?php

use yii\helpers\Html;

/* @var integer $count */
/* @var array $reminders */
/* @var array $remindInOptions */
/* @var \hiqdev\yii2\reminder\models\Reminder $reminder */
/* @var string $loaderTemplate */

?>
<!-- Menu toggle button -->
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span id="reminder-count"
          class="label label-warning reminder-counts <?= $count > 0 ? '' : 'hidden' ?>"><?= $count ?></span>
</a>
<ul class="dropdown-menu">
    <li class="header">
        <?= Yii::t('hiqdev:yii2:reminder', 'Reminders') ?>
    </li>
    <li class="reminder-body">
        <?= $loaderTemplate ?>
    </li>
    <li class="footer"><?= Html::a(Yii::t('hiqdev:yii2:reminder', 'View all'), ['/reminder/reminder/index']) ?></li>

</ul>
