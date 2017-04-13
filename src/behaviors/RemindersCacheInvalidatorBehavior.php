<?php

namespace hiqdev\yii2\reminder\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class RemindersCacheInvalidatorBehavior extends Behavior
{
    const TOTAL_COUNT_CACHE_KEY = 'reminders-total-count';

    /**
     * @var array Actions that will be affected by this behavior
     */
    public $actions = [];

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'tryToInvalidate'
        ];
    }

    public function tryToInvalidate($event)
    {
        if (in_array($event->sender->action->id, $this->actions, true)) {
            $this->invalidateCache();
        }
    }

    public static function totalCountCacheKey()
    {
        return [self::TOTAL_COUNT_CACHE_KEY, Yii::$app->user->id];
    }

    private function invalidateCache()
    {
        Yii::$app->cache->delete($this->totalCountCacheKey());
    }
}
