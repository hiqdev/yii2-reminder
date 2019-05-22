<?php
/**
 * Reminder module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-reminder
 * @package   yii2-reminder
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\reminder\widgets;

use hipanel\assets\BootstrapDatetimepickerAsset;
use hipanel\widgets\AjaxModal;
use omnilight\assets\MomentAsset;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class ReminderButton extends Widget
{
    public $object_id;

    public $toggleButton = [];

    public $toggleButtonOptions = [];

    public function init()
    {
        $this->registerClientScript();

        $this->view->on(View::EVENT_END_BODY, function ($event) {
            echo AjaxModal::widget([
                'bulkPage' => false,
                'id' => $this->getModalId(),
                'successText' => Yii::t('hiqdev:yii2:reminder', 'Reminder has been created'),
                'modalFormId' => 'reminder-form-' . $this->object_id,
                'scenario' => 'create',
                'actionUrl' => ['/reminder/reminder/create-modal', 'object_id' => $this->object_id],
                'handleSubmit' => Url::toRoute('/reminder/reminder/create'),
                'size' => Modal::SIZE_DEFAULT,
                'header' => Html::tag('h4', Yii::t('hiqdev:yii2:reminder', 'Create new reminder'), ['class' => 'modal-title']),
                'toggleButton' => false,
            ]);
        });
    }

    public function run()
    {
        $modalId = $this->getModalId();
        $this->view->registerCss("button[data-target='#{$modalId}'] {margin-top: -3px;} #{$modalId} .datepicker > div { display: inherit; }");

        return Html::button('<i class="fa fa-bell-o"></i>&nbsp;' . Yii::t('hiqdev:yii2:reminder', 'Create reminder'), array_merge([
            'class' => 'btn margin-bottom btn-info btn-xs pull-right',
            'data' => [
                'toggle' => 'modal',
                'target' => "#{$modalId}",
            ],
        ], $this->toggleButtonOptions));
    }

    /**
     * @return mixed
     */
    public function getToggleButton()
    {
        return (!empty($this->toggleButton)) ?
            $this->toggleButton :
            [
                'label' => '<i class="fa fa-bell-o"></i>&nbsp;&nbsp;' . Yii::t('hiqdev:yii2:reminder', 'Create reminder'),
                'class' => 'btn margin-bottom btn-info btn-xs pull-right',
            ];
    }

    /**
     * @param mixed $toggleButton
     */
    public function setToggleButton($toggleButton)
    {
        $this->toggleButton = $toggleButton;
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        MomentAsset::register($view);
        BootstrapDatetimepickerAsset::register($view);
    }

    private function getModalId()
    {
        return 'reminder-modal-' . $this->object_id;
    }
}
