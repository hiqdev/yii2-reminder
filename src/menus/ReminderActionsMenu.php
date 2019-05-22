<?php

namespace hiqdev\yii2\reminder\menus;

use hiqdev\yii2\menus\Menu;
use hiqdev\yii2\reminder\models\Reminder;
use Yii;

class ReminderActionsMenu extends Menu
{
    /**
     * @var Reminder
     */
    public $model;

    public function items(): array
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@reminder/view', 'id' => $this->model->id],
            ],
        ];
    }
}
