<?php

use humhub\modules\content\widgets\richtext\ProsemirrorRichText;
use humhub\modules\content\widgets\richtext\ProsemirrorRichTextEditor;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentAddonActiveRecord;

return [
    'id' => 'linkpreview',
    'class' => 'humhub\modules\linkpreview\Module',
    'namespace' => 'humhub\modules\linkpreview',
    'events' => [
        // Save
       ['class' => ProsemirrorRichTextEditor::class, 'event' => ProsemirrorRichTextEditor::EVENT_AFTER_RUN, 'callback' => ['humhub\modules\linkpreview\Module', 'onRichTextEditorFieldCreate']],
        ['class' => ContentActiveRecord::class, 'event' => ContentActiveRecord::EVENT_AFTER_INSERT, 'callback' => ['humhub\modules\linkpreview\Module', 'onAfterContentSave']],
        ['class' => ContentActiveRecord::class, 'event' => ContentActiveRecord::EVENT_AFTER_UPDATE, 'callback' => ['humhub\modules\linkpreview\Module', 'onAfterContentSave']],
        ['class' => ContentAddonActiveRecord::class, 'event' => ContentAddonActiveRecord::EVENT_AFTER_INSERT, 'callback' => ['humhub\modules\linkpreview\Module', 'onAfterContentSave']],
        ['class' => ContentAddonActiveRecord::class, 'event' => ContentAddonActiveRecord::EVENT_AFTER_UPDATE, 'callback' => ['humhub\modules\linkpreview\Module', 'onAfterContentSave']],
        // Output
       ['class' => ProsemirrorRichText::class, 'event' => ProsemirrorRichText::EVENT_BEFORE_OUTPUT, 'callback' => ['humhub\modules\linkpreview\Module', 'onRichTextOutput']],
    ],
];
?>