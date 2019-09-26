<?php

use humhub\modules\activity\widgets\ActivityStreamViewer;
use humhub\modules\space\modules\manage\widgets\PendingApprovals;
use humhub\modules\space\widgets\Members;
use humhub\modules\space\widgets\Sidebar;
use yii\helpers\Html;

$cssClass = ($page->hasAttribute('cssClass') && !empty($page->cssClass)) ? $page->cssClass :  'custom-pages-page';
?>
<div class="panel panel-default <?= Html::encode($cssClass) ?>">
    <div class="panel-body">
        <?= humhub\widgets\MarkdownView::widget(['markdown' => $md]); ?>
        <?php
        // Custom
        $this->beginBlock('sidebar');

        echo Sidebar::widget(['space' => $contentContainer, 'widgets' => [
            [ActivityStreamViewer::class, ['contentContainer' => $contentContainer], ['sortOrder' => 10]],
            [PendingApprovals::class, ['space' => $contentContainer], ['sortOrder' => 20]],
            [Members::class, ['space' => $contentContainer], ['sortOrder' => 30]],
        ]]);

        $this->endBlock();
        // Custom
        ?>
    </div>
</div>
