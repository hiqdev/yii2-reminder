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

use DateTime;
use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\helpers\Url;
use Yii;

class Reminder extends Model
{
    use ModelTrait;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    const TYPE_SITE = 'site';
    const TYPE_MAIL = 'mail';

    public $offset;
    public $reminderChange;

    public function init()
    {
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'updateNextTime']);
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'insertWithClientOffset']);
    }

    public static function reminderNextTimeOptions()
    {
        return [
            '+15 minutes' => Yii::t('hiqdev:yii2:reminder', '15m'),
            '+30 minutes' => Yii::t('hiqdev:yii2:reminder', '30m'),
            '+1 hour' => Yii::t('hiqdev:yii2:reminder', '1h'),
            '+12 hours' => Yii::t('hiqdev:yii2:reminder', '12h'),
            '+1 day' => Yii::t('hiqdev:yii2:reminder', '1d'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'client_id', 'state_id', 'type_id'], 'integer'],
            [['class_name', 'periodicity', 'from_time', 'till_time', 'next_time', 'periodicity_label'], 'string'],
            [['to_site'], 'boolean'],

            // Create
            [['object_id', 'type', 'periodicity', 'from_time', 'offset'], 'required', 'on' => self::SCENARIO_CREATE],
            [['message', 'next_time'], 'string', 'on' => self::SCENARIO_CREATE],

            // Update
            [['id'], 'required', 'on' => 'update'],
            [['object_id', 'state_id', 'type_id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['from_time', 'next_time', 'till_time', 'reminderChange', 'offset'], 'string', 'on' => self::SCENARIO_UPDATE],

            // Delete
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'periodicity' => Yii::t('hiqdev:yii2:reminder', 'Periodicity'),
            'from_time' => Yii::t('hiqdev:yii2:reminder', 'When the recall?'),
            'next_time' => Yii::t('hiqdev:yii2:reminder', 'Next time'),
            'till_time' => Yii::t('hiqdev:yii2:reminder', 'Remind till'),
            'message' => Yii::t('hiqdev:yii2:reminder', 'Message'),
        ]);
    }

    public function getObjectName()
    {
        $result = '';
        if ($this->class_name) {
            switch ($this->class_name) {
                case 'thread':
                    $result = 'ticket';
                    break;
            }
        }

        return $result;
    }

    public function getPeriodicityNextTime()
    {
        $modify = (ctype_digit(substr($this->periodicity, 0, 1))) ? '+ ' . $this->periodicity : '+1 ' . $this->periodicity;
        return $modify;
    }

    /**
     * {@inheritdoc}
     * @return ReminderQuery
     */
    public static function find($options = [])
    {
        return new ReminderQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    /**
     * @return bool
     */
    public function updateNextTime()
    {
        if ($this->scenario === self::SCENARIO_UPDATE) {
            $this->next_time = (new DateTime($this->next_time))->modify($this->reminderChange)->format('Y-m-d H:i:s');
        }
    }

    protected function getNextTime()
    {
        return (new DateTime($this->next_time))->modify($this->reminderChange)->format('Y-m-d H:i:s');
    }

    public function insertWithClientOffset()
    {
        if ($this->scenario === self::SCENARIO_CREATE) {
            $offset = $this->toServerTime($this->offset);
            $modyfy = $offset . ' minutes';
            $this->from_time = $this->next_time = (new DateTime($this->from_time))->modify($modyfy)->format('Y-m-d H:i:s');
        }
    }

    public function calculateClientNextTime($offset)
    {
        $next_time = (new DateTime($this->next_time))->modify($this->toClientTime($offset) . ' minutes');
        return Yii::$app->formatter->asDatetime($next_time->modify($this->periodicityNextTime), 'short');
    }

    protected function getSign($offset)
    {
        return (strpos($offset, '-') === false) ? '+' : '-';
    }

    protected function toServerTime($offset)
    {
        if ($this->getSign($offset) === '+') {
            return '-' . $offset;
        } else {
            return $offset;
        }
    }

    protected function toClientTime($offset)
    {
        if ($this->getSign($offset) === '+') {
            return '+' . $offset;
        } else {
            return $offset;
        }
    }

    public function getObjectLabel()
    {
        return Yii::t('hiqdev:yii2:reminder', '{0} #{1}', [Yii::t('hiqdev:yii2:reminder', ucfirst($this->objectName)), $this->object_id]);
    }

    public function getObjectLink()
    {
        return Url::toRoute([sprintf('@%s/view', $this->objectName), 'id' => $this->object_id]);
    }
}
