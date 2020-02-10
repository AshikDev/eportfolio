<?php

namespace humhub\modules\linkpreview\models\forms;
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/**
 * Description of LinkPreviewForm
 *
 * @author buddha
 */
class LinkPreviewForm extends \humhub\modules\linkpreview\models\LinkPreview
{
    /**
     * Loads the given request data into the form.
     * Returns true if the data could be loaded and the title was set successfully.
     * 
     * @param type $data
     * @param type $formName
     * @return type
     */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        return $result && !empty($this->title);
    }
    
}
