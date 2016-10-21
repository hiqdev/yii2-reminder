<?php

namespace hiqdev\yii2\reminder\models;

use DateTime;
use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\helpers\Url;
use Yii;

class Reminder extends Model
{
    use ModelTrait;

    public static $i18nDictionary = 'hiqdev/yii2/reminder';

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
            '+15 minutes' => Yii::t(self::$i18nDictionary, '15m'),
            '+30 minutes' => Yii::t(self::$i18nDictionary, '30m'),
            '+1 hour' => Yii::t(self::$i18nDictionary, '1h'),
            '+12 hours' => Yii::t(self::$i18nDictionary, '12h'),
            '+1 day' => Yii::t(self::$i18nDictionary, '1d'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'client_id', 'state_id', 'type_id'], 'integer'],
            [['class_name', 'periodicity', 'from_time', 'till_time', 'next_time'], 'string'],
            [['to_site'], 'boolean'],

            // Create
            [['object_id', 'type', 'periodicity', 'from_time', 'offset'], 'required', 'on' => self::SCENARIO_CREATE],
            [['message'], 'string', 'on' => self::SCENARIO_CREATE],

            // Update
            [['id'], 'required', 'on' => 'update'],
            [['object_id', 'state_id', 'type_id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['from_time', 'next_time', 'till_time', 'reminderChange', 'offset'], 'string', 'on' => self::SCENARIO_UPDATE],

            // Delete
            [['id'], 'required', 'on' => self::SCENARIO_DELETE]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'periodicity' => Yii::t(self::$i18nDictionary, 'Periodicity'),
            'from_time' => Yii::t(self::$i18nDictionary, 'When the recall?'),
            'next_time' => Yii::t(self::$i18nDictionary, 'Next time'),
            'till_time' => Yii::t(self::$i18nDictionary, 'Remind till'),
            'message' => Yii::t(self::$i18nDictionary, 'Message'),
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
        if ($this->scenario == self::SCENARIO_UPDATE) {
            $this->next_time = (new DateTime($this->next_time))->modify($this->reminderChange)->format('Y-m-d H:i:s');
        }
    }

    public function insertWithClientOffset()
    {
        if ($this->scenario == self::SCENARIO_CREATE) {
            $offset = $this->toServerTime($this->offset);
            $this->from_time = (new DateTime($this->from_time))->modify($offset . ' minutes')->format('Y-m-d H:i:s');
        }
    }

    public function calculateClientNextTime($offset)
    {
        $next_time = (new DateTime($this->next_time))->modify($this->toClientTime($offset) . ' minutes');
        return Yii::$app->formatter->asDatetime($next_time, 'short');
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
        return Yii::t('hiqdev/yii2/reminder', "{0} ID #{1}", [Yii::t('hiqdev/yii2/reminder', ucfirst($this->objectName)), $this->object_id]);
    }

    public function getObjectLink()
    {
        return Url::toRoute([sprintf("@%s/view", $this->objectName), 'id' => $this->object_id]);
    }
}
