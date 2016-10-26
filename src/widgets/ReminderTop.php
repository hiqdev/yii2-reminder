<?php

namespace hiqdev\yii2\reminder\widgets;

use hiqdev\yii2\reminder\models\Reminder;
use hiqdev\yii2\reminder\ReminderTopAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;

class ReminderTop extends Widget
{
    public $loaderTemplate = '<div class="reminder-ajax-loader text-center text-muted"><i class="fa fa-refresh fa-2x fa-spin fa-fw"></i></div>';

    public function init()
    {
        parent::init();
        Yii::$app->assetManager->forceCopy = true;
        $reminderOptions = Json::encode([
            'listUrl' => Url::to('/reminder/reminder/ajax-reminders-list'),
            'deleteUrl' => Url::to('/reminder/reminder/delete'),
            'updateUrl' => Url::to('/reminder/reminder/update'),
            'getCountUrl' => Url::to('/reminder/reminder/get-count'),
            'updateText' => Yii::t('hiqdev/yii2/reminder', 'Reminders deferred'),
            'doNotRemindText' => Yii::t('hiqdev/yii2/reminder', 'Reminder removed'),
            'loaderTemplate' => $this->loaderTemplate,
        ]);
        $this->registerClientScript($reminderOptions);
    }

    public function run()
    {
        $count = Reminder::find()->toSite()->own()->count();
        $remindInOptions = Reminder::reminderNextTimeOptions();
        return $this->render('ReminderTop', [
            'count' => $count,
            'remindInOptions' => $remindInOptions,
            'loaderTemplate' => $this->loaderTemplate
        ]);
    }

    public function registerClientScript($options)
    {
        $view = $this->getView();
        ReminderTopAsset::register($view);
        $view->registerJs("$('#reminders').reminder({$options});");
    }
}
