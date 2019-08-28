<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\popovervcard\models;

use humhub\modules\popovervcard\Module;
use Yii;

class Configuration extends \yii\base\Model
{


    public $userEnabled;
    public $userContent;
    public $userDefaultContent = "{% if profile.about %}\n\t{{ profile.about|e }}\n{% else %}\n\tNo user description available.\n{% endif %}";

    public $spaceEnabled;
    public $spaceContent;
    public $spaceDefaultContent = "{% if space.description %}\n\t{{ space.description|e }}\n{% else %}\n\tNo space description available.\n{% endif %}";

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userEnabled', 'spaceEnabled'], 'boolean'],
            [['userContent', 'spaceContent'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userEnabled' => 'Enabled',
            'spaceEnabled' => 'Enabled',
            'userContent' => 'Content',
            'spaceContent' => 'Content',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
        ];
    }

    public function loadSettings()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('popover-vcard');

        $settings = $module->settings;

        $this->userEnabled = (int)$settings->get('userEnabled', 1);
        $this->userContent = $settings->get('userContent', $this->userDefaultContent);

        $this->spaceEnabled = (int)$settings->get('spaceEnabled', 1);
        $this->spaceContent = $settings->get('spaceContent', $this->spaceDefaultContent);

        return true;
    }

    public function saveSettings()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('popover-vcard');

        $settings = $module->settings;
        $settings->set('userEnabled', (int)$this->userEnabled);
        $settings->set('spaceEnabled', (int)$this->spaceEnabled);

        if (empty($this->spaceContent)) {
            $settings->delete('spaceContent');
        } else {
            $settings->set('spaceContent', $this->spaceContent);
        }

        if (empty($this->userContent)) {
            $settings->delete('userContent');
        } else {
            $settings->set('userContent', $this->userContent);
        }

        return true;
    }

}
