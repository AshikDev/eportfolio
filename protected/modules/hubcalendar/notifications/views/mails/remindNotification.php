<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\modules\hubcalendar\models\CalendarDateFormatter;
use humhub\modules\hubcalendar\widgets\mails\CalendarEventMailInfo;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\widgets\mails\MailButtonList;
use humhub\widgets\mails\MailButton;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $viewable humhub\modules\content\notifications\ContentCreated */
/* @var $url string */
/* @var $date string */
/* @var $isNew boolean */
/* @var $isNew boolean */
/* @var $originator \humhub\modules\user\models\User */
/* @var $source \humhub\modules\hubcalendar\interfaces\reminder\CalendarEventReminderIF */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $space humhub\modules\space\models\Space */
/* @var $record \humhub\modules\notification\models\Notification */
/* @var $html string */
/* @var $text string */

?>
<?php $this->beginContent('@notification/views/layouts/mail.php', $_params_); ?>

    <?= CalendarEventMailInfo::html($source, $url) ?>

<?php $this->endContent();
