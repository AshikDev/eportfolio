<?php

namespace humhub\modules\linkpreview\widgets;

use yii\helpers\Url;
use humhub\modules\linkpreview\models\forms\LinkPreviewForm;

class Editor extends \humhub\widgets\JsWidget
{

    /**
     * @inheritdoc
     */
    public $jsWidget = "linkpreview.LinkPreviewEditor";

    /**
     * @inheritdoc
     */
    public $visible = false;

    /**
     * @var \humhub\components\ActiveRecord the link preview belongs to
     */
    public $record;

    /**
     * @var string the Id of input element
     */
    public $richtextId;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->record) {
            $model = new LinkPreviewForm();
        } else {
            $model = LinkPreviewForm::findByRecord($this->record);
            if (!$model) {
                $model = new LinkPreviewForm();
                $model->class = $this->record->className();
                $model->pk = $this->record->getPrimaryKey();
            }
        }

        if (!$model->isNewRecord) {
            $this->visible = true;
            $this->init = true;
        }

        return $this->render('edit', [
                    'options' => $this->getOptions(),
                    'model' => $model,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'class' => 'content-preview preview-editor'
        ];
    }

    public function getData()
    {
        return [
            'fetch-url' => Url::to(['/linkpreview/index/fetch']),
            'richtext-selector' => '#' . $this->richtextId
        ];
    }

}
