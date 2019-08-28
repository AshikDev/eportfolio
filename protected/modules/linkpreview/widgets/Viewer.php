<?php

namespace humhub\modules\linkpreview\widgets;

use humhub\modules\linkpreview\models\LinkPreview;

class Viewer extends \humhub\widgets\JsWidget
{

    /**
     * @var \humhub\components\ActiveRecord the link preview belongs to
     */
    public $record = null;
    
    /**
     * @inheritdoc
     */
    public $jsWidget = "linkpreview.LinkPreview";
    
    /**
     * @inheritdoc
     */
    public $visible = false;
    
    /**
     * @inheritdoc
     */
    public $init = true;
    
    /**
     * @var LinkPreview instance
     */
    private $linkPreview;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if(!$this->record) {
            return;
        }
        
        $this->linkPreview = LinkPreview::findOne(['class' => $this->record->className(), 'pk' => $this->record->getPrimaryKey()]);

        if (!$this->linkPreview) {
            return;
        }
        
        return $this->render('view', ['linkPreview' => $this->linkPreview, 'options' => $this->getOptions()]);
    }
    
    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'class' => 'content-preview'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getData()
    {
        return [
            'richtext-feature' => true
        ];
    }
}
