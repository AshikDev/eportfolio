<?php

namespace humhub\modules\linkpreview;

use Yii;
use humhub\modules\content\widgets\richtext\ProsemirrorRichTextEditor;
use humhub\modules\linkpreview\models\forms\LinkPreviewForm;

class Module extends \humhub\components\Module
{

    /**
     * Adds the linkpreview editor to the richtext editor field.
     * @param type $event
     */
    public static function onRichTextEditorFieldCreate($event)
    {

        if(static::validateEditorSender($event)) {
            $event->result .= widgets\Editor::widget([
                'richtextId' => $event->sender->id,
                'record' => $event->sender->model
            ]);
        }
    }

    private static function validateEditorSender($event) {
        /* @var $richtext ProsemirrorRichTextEditor */
        $richtext = $event->sender;

        if(!$richtext->id || !($richtext instanceof ProsemirrorRichTextEditor)) {
            return false;
        }

        return $richtext->id === 'contentForm_message'
                || strpos($richtext->id, 'newCommentForm_') === 0
                || strpos($richtext->id, 'comment_input_') === 0
                || strpos($richtext->id, 'post_input_') === 0;
    }

    /**
     * Appends the linkpreview to the richtext output.
     * @param type $event
     */
    public static function onRichTextOutput($event)
    {
        if($event->sender->record instanceof \yii\db\ActiveRecord) {
            $event->parameters['output'] .= widgets\Viewer::widget([
                'record' => $event->sender->record
            ]);
        }
    }

    /**
     * Saves the linkpreview for a given
     * @param type $event
     * @return type
     */
    public static function onAfterContentSave($event)
    {
        if (Yii::$app->request->isConsoleRequest) {
            return;
        }

        if ($event->sender instanceof \humhub\modules\activity\models\Activity) {
            return;
        }

        if(!($event->sender instanceof \yii\db\ActiveRecord)) {
            return;
        }

        $preview = LinkPreviewForm::findByRecord($event->sender);

        if (!$preview) {
            $preview = new LinkPreviewForm();
            $preview->class = $event->sender->className();
            $preview->pk = $event->sender->getPrimaryKey();
        }

        if ($preview->load(Yii::$app->request->post())) {
            $preview->save();
        } else if (!$preview->isNewRecord) {
            $preview->delete();
        }
    }

}
