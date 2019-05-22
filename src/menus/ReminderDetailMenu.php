<?php

namespace hiqdev\yii2\reminder\menus;

use hipanel\menus\AbstractDetailMenu;
use Yii;

class ReminderDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = ReminderActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, [
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@reminder/delete', 'id' => $this->model->id],
                'encode' => false,
//                'visible' => Yii::$app->user->can('reminder.delete'),
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
            ],
        ]);
        unset($items['view']);

        return $items;
    }
}
