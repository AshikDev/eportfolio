<?php
use humhub\modules\calendar\widgets\CalendarFilterBarCustom;
use humhub\modules\calendar\widgets\FullCalendarCustom;
use humhub\widgets\Button;
use humhub\widgets\FadeIn;

$configUrl = $contentContainer->createUrl('/calendar/container-config');
$loadAjaxUrl = $contentContainer->createUrl('/calendar/view/load-ajax');

?>
<div class="panel panel-default">
    <div class="panel-body" style="background-color:<?= $this->theme->variable('background-color-secondary') ?>">
        <?= CalendarFilterBarCustom::widget([
            'filters' => $filters,
            'canConfigure' => $canConfigure,
            'configUrl' => $configUrl,
            'showSelectors' => false,
            'community' => $contentContainer->community,
            'space_id' => $contentContainer->id
            ]) ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <?= FullCalendarCustom::widget([
                    'canWrite' => $canAddEntries,
                    'loadUrl' => $loadAjaxUrl,
                    'contentContainer' => $contentContainer]);
                ?>
            </div>
        </div>
    </div>
</div>