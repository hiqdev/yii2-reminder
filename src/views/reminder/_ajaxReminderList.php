<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var array $remindInOptions */
/* @var array $reminders */
/* @var string $offset */

?>
<ul id="reminder-menu-dropdown" class="menu">
    <?php if (!empty($reminders)) : ?>
        <?php foreach ($reminders as $reminder) : ?>
            <li id="reminder-<?= $reminder->id ?>">
                <div>
                    <div class="row">
                        <div class="col-xs-6">
                            <?= Html::a($reminder->objectLabel, $reminder->objectLink, ['class' => 'r-title']) ?>
                        </div>
                        <div class="col-xs-6 text-right">
                            <small class="r-time"><?= $reminder->calculateClientNextTime($offset) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="r-text"><?= StringHelper::truncateWords(Html::encode($reminder->message), 15) ?></p>
                        </div>
                    </div>
                    <div class="row r-actions">
                        <div class="col-xs-6">
                            <div class="btn-group">
                                <?php
                                if ($reminder->periodicity === 'once') {
                                    $options = [
                                        'class' => 'btn btn-default btn-xs reminder-update',
                                        'data' => [
                                            'reminder-id' => $reminder->id,
                                            'reminder-action' => '+15 minutes',
                                        ],
                                    ];
                                } else {
                                    $options = [
                                        'class' => 'btn btn-default btn-xs reminder-update reminder-defer',
                                        'data' => [
                                            'reminder-id' => $reminder->id,
                                            'container' => 'body',
                                            'toggle' => 'popover',
                                            'placement' => 'bottom',
                                        ],
                                    ];
                                }
                                echo Html::button(Yii::t('hiqdev:yii2:reminder', 'Defer'), $options);
                                ?>
                                <button type="button" class="btn btn-default btn-xs reminder-defer" data-reminder-id="<?= $reminder->id ?>" data-container="body" data-toggle="popover" data-placement="bottom">
                                    <span class="caret"></span>
                                </button>
                                <div id="popover-<?= $reminder->id ?>" style="display: none">
                                    <?php foreach ($remindInOptions as $time => $label) : ?>
                                        <?= Html::button(
                                            Yii::t('hiqdev:yii2:reminder', $label),
                                            [
                                                'class' => 'btn btn-xs btn-link reminder-update',
                                                'data' => [
                                                    'reminder-id' => $reminder->id,
                                                    'reminder-action' => $time,
                                                ],
                                            ]
                                        ) ?>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <?= Html::button(Yii::t('hiqdev:yii2:reminder', 'Don\'t remind'), [
                                'class' => 'btn btn-xs reminder-delete btn-link text-danger',
                                'data' => [
                                    'reminder-id' => $reminder->id,
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <!--small>
                        <?= Yii::t('hiqdev:yii2:reminder', 'Remind in') ?>:
                        <?php foreach ($remindInOptions as $time => $label) : ?>
                            <?= Html::button(
                        Yii::t('hiqdev:yii2:reminder', $label),
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
                            <?= Html::button(Yii::t('hiqdev:yii2:reminder', 'Remind next time'), [
                        'class' => 'btn btn-xs btn-block btn-info reminder-update lg-mt-10 md-mt-10',
                        'data' => [
                            'reminder-id' => $reminder->id,
                            'reminder-action' => $reminder->periodicityNextTime,
                        ],
                    ]) ?>
                        <?php endif; ?>
                        <?= Html::button(Yii::t('hiqdev:yii2:reminder', 'Don\'t remind'), [
                        'class' => 'btn btn-xs btn-block btn-danger reminder-delete lg-mt-10 md-mt-10',
                        'data' => [
                            'reminder-id' => $reminder->id,
                        ],
                    ]) ?>
                    </small-->
                </div>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <li class="margin text-muted"
            style="font-size: small"><?= Yii::t('hiqdev:yii2:reminder', 'You have no reminders') ?></li>
    <?php endif ?>
</ul>
