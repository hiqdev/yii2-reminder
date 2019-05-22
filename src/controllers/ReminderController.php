<?php
/**
 * Reminder module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-reminder
 * @package   yii2-reminder
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\reminder\controllers;

use DateTime;
use hipanel\actions\IndexAction;
use hipanel\actions\RenderAjaxAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hiqdev\yii2\reminder\behaviors\RemindersCacheInvalidatorBehavior;
use hiqdev\yii2\reminder\models\Reminder;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReminderController extends CrudController
{
    /// TODO extend from yii\web\Controller:
    /// - no need to change viewPath
    /// - abstract getting refs
    public function init()
    {
        parent::init();

        $this->viewPath = '@hiqdev/yii2/reminder/views/reminder';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'remindersCacheInvalidator' => [
                'class' => RemindersCacheInvalidatorBehavior::class,
                'actions' => [
                    'create',
                    'update',
                    'delete',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
            ],
            'create-modal' => [
                'class' => SmartCreateAction::class,
                'scenario' => 'create',
                'view' => 'create-modal',
                'data' => function ($action, $data) {
                    $object_id = Yii::$app->request->get('object_id');
                    if (empty($object_id)) {
                        throw new NotFoundHttpException('Object ID is missing');
                    }
                    $data['model']->object_id = $object_id;

                    return $data;
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'view' => 'create-modal',
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function ($event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    if (Yii::$app->request->isAjax) {
                        $reminder = Yii::$app->request->post('Reminder');
                        $action->collection->set(Reminder::find()->where(['id' => $reminder['id']])->one());
                        foreach ($action->collection->models as $model) {
                            $model->next_time = (new DateTime($model->next_time))->modify($reminder['reminderChange'])->format('Y-m-d H:i:s');
                        }
                    }
                },
                'POST ajax' => [
                    'save' => true,
                    'flash' => false,
                    'success' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            return [
                                'success' => true,
                            ];
                        },
                    ],
                ],
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hiqdev:yii2:reminder', 'Reminder has been removed'),
            ],
            'ajax-reminders-list' => [
                'class' => RenderAjaxAction::class,
                'view' => '_ajaxReminderList',
                'params' => function () {
                    $reminders = Reminder::find()->own()->triggered()->toSite()->all();
                    $remindInOptions = Reminder::reminderNextTimeOptions();
                    $offset = Yii::$app->request->post('offset');

                    return compact('reminders', 'remindInOptions', 'offset');
                },
            ],
        ];
    }

    public function actionGetCount()
    {
        if (!Yii::$app->request->isAjax) {
            return;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->getIsGuest() || empty(Yii::$app->user->identity->getId())) {
            return [
                'preventUpdates' => true,
            ];
        }

        if (Yii::$app->cache->get(RemindersCacheInvalidatorBehavior::totalCountCacheKey()) === 0) {
            return ['count' => 0];
        }

        return [
            'count' => Reminder::find()->own()->triggered()->toSite()->count(),
        ];
    }
}
