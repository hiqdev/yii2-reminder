<?php
/**
 * Reminder module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-reminder
 * @package   yii2-reminder
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\reminder\models;

use hiqdev\hiart\ActiveQuery;
use Yii;

class ReminderQuery extends ActiveQuery
{
    public function toSite()
    {
        $this->andWhere([
            'to_site' => true,
        ]);

        return $this;
    }

    public function own()
    {
        $this->andWhere([
            'client_id' => Yii::$app->user->identity->id,
        ]);

        return $this;
    }

    public function all($db = null)
    {
        $this->own();

        return parent::all($db);
    }
}
