<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @var array $remindInOptions */
/* @var array $reminders */
/* @var string $offset */

?>
<ul class="menu">
    <?php if (!empty($reminders)) : ?>
        <?php foreach ($reminders as $reminder) : ?>
            <li id="reminder-<?= $reminder->id ?>">
                <div>
                    <h4>
                        <?= Html::beginTag('a', ['href' => $reminder->objectLink]) ?>
                        <?= $reminder->objectLabel ?>
                        <small>
                            <?= Yii::t('hiqdev/yii2/reminder', 'Next time') ?>
                            : <?= $reminder->calculateClientNextTime($offset) ?>
                        </small>
                        <?= Html::endTag('a') ?>
                    </h4>
                    <p>
                        <?= StringHelper::truncateWords(Html::encode($reminder->message), 3) ?>
                    </p>
                    <small>
                        <?= Yii::t('hiqdev/yii2/reminder', 'Remind in') ?>:
                        <?php foreach ($remindInOptions as $time => $label) : ?>
                            <?= Html::button(
                                Yii::t('hiqdev/yii2/reminder', $label),
                                [
                                    'class' => 'btn btn-xs btn-link reminder-update',
                                    'data' => [
                                        'reminder-id' => $reminder->id,
                                        'reminder-action' => $time,
                                    ],
                                ]
                            ) ?>
                        <?php endforeach ?>
                        <br>
                        <?php if ($reminder->periodicity !== 'once') : ?>
                            <?= Html::button(Yii::t('hiqdev/yii2/reminder', 'Remind next time'), [
                                'class' => 'btn btn-xs btn-block btn-info reminder-update lg-mt-10 md-mt-10',
                                'data' => [
                                    'reminder-id' => $reminder->id,
                                    'reminder-action' => $reminder->periodicityNextTime,
                                ],
                            ]) ?>
                        <?php endif; ?>
                        <?= Html::button(Yii::t('hiqdev/yii2/reminder', 'Don\'t remind'), [
                            'class' => 'btn btn-xs btn-block btn-danger reminder-delete lg-mt-10 md-mt-10',
                            'data' => [
                                'reminder-id' => $reminder->id,
                            ],
                        ]) ?>
                    </small>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <li class="margin text-muted"
            style="font-size: small"><?= Yii::t('hiqdev/yii2/reminder', 'You have no reminders') ?></li>
    <?php endif ?>
</ul>
