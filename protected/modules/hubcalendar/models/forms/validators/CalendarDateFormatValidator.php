<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\hubcalendar\models\forms\validators;

use Yii;
use yii\validators\Validator;
use humhub\modules\hubcalendar\helpers\CalendarUtils;
use humhub\modules\hubcalendar\models\forms\CalendarEntryForm;

/**
 * Validates a date time field
 *
 * @package humhub\modules\hubcalendar\models\forms\validators
 */
class CalendarDateFormatValidator extends Validator
{
    public $timeField;

    /**
     * @param CalendarEntryForm $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $timeValue = $model->{$this->timeField};

        try {
            $parsed = CalendarUtils::parseDateTimeString($value, $timeValue);
            if (!$parsed) {
                throw new \Exception('Invalid date time format: ' . $value . 'with time: ' . $timeValue);
            }
        } catch (\Exception $e) {
            $this->addError($model, $attribute, Yii::t('CalendarModule.base', 'Invalid date or time format!'));
            Yii::warning($e);
        }
    }
}