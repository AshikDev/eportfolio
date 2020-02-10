<?php

use humhub\modules\activity\widgets\ActivityStreamViewer;
use humhub\modules\space\modules\manage\widgets\PendingApprovals;
use humhub\modules\space\widgets\Members;
use humhub\modules\space\widgets\Sidebar;
use yii\helpers\Html;

$cssClass = ($page->hasAttribute('cssClass') && !empty($page->cssClass)) ? $page->cssClass : 'custom-pages-page';
?>

<iframe class="<?= Html::encode($cssClass) ?>" id="iframepage" style="width:100%; height: 100%; overflow: hidden;" src="<?= Html::encode($url); ?>"></iframe>

<style>
    #iframepage {
        border: none;
        margin-top: 0;
        background: url('<?= Yii::$app->moduleManager->getModule('custom_pages')->getPublishedUrl('/loader.gif'); ?>') center center no-repeat;
    }
</style>

<script>
    function setSize() {
        $('#iframepage').css( {
            height: ($('#iframepage').position().top) + 'px',
            background: 'inherit'
        });
    }

    window.onresize = function (evt) {
        setSize();
    };

    $(document).on('humhub:ready', function () {
        $('#iframepage').on('load', function () {
            setSize();
        });
    });
</script>

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
