<?php

use yii\helpers\Html;
use humhub\modules\tasks\models\Task;

\humhub\modules\meeting\assets\PrintAssets::register($this);

 $meeting->getItemsPopulated();

?>

<div class="print-body">
    <h1><strong><?= Html::encode($meeting->title); ?></strong></h1>
    <h2>
        <?= Yii::$app->formatter->asDate($meeting->date); ?>
        at <?= substr($meeting->begin, 0, 5); ?> - <?= substr($meeting->end, 0, 5); ?>
        <?= Html::encode($meeting->location) ?> <?php
        if ($meeting->room != null) {
            echo "(" . Html::encode($meeting->room) . ")";
        }
        ?>
    </h2>

    <em><strong><?= Yii::t('MeetingModule.views_index_index', 'Participants') ?>:</strong></em><br>

    <?=
    implode(", ", array_map(function ($p) {
                return ($p->getUser()) ? Html::encode($p->getUser()->displayName) : Html::encode($p->name);
            }, $meeting->participants));
    ?>
    <?php if ($meeting->external_participants != null) : ?>
        <br><br>
        <em><strong><?= Yii::t('MeetingModule.views_index_index', 'External participants') ?>:</strong></em><br>
        <?= $meeting->external_participants; ?>
    <?php endif; ?>
    <hr>
</div>


<div class="meeting-details">
    <div class="meeting-item-container">
        <div class="agenda-time-line">
            <?php foreach ($meeting->items as $item): ?>
                <div class="agenda-point"><i style="font" class="fa fa-circle" aria-hidden="true"></i></div>
                <div style="margin-left:40px;padding-right:15px;">
                    
                    <!-- Title + Description start -->
                    <h1>
                        <?php if ($item->begin != "00:00" && $item->end != "00:00") : ?>
                            <?= Html::encode($item->getTimeRangeText()); ?> -
                        <?php endif; ?>
                        <?= Html::encode($item->title); ?>
                    </h1>
                    <?= \humhub\widgets\MarkdownView::widget(array('markdown' => $item->description)); ?>
                    <!-- Title + Description end -->
                    
                    <br />
                    
                    <!-- Meeting Infos start -->
                    <table class="meeting-print" style="border-spacing: 5px;">
                       
                        <!-- MODERATORS start -->
                        <tr>
                            <td style="vertical-align: top;width:120px;">
                                <strong><?= Yii::t('MeetingModule.views_index_index', 'Moderators'); ?></strong>:&nbsp; 
                            </td>
                            <td style="vertical-align: top;">
                                <?=
                                implode(", ", array_map(function ($p) {
                                            return ($p->getUser()) ? Html::encode($p->user->displayName) : Html::encode($p->name);
                                        }, $item->moderators));
                                ?>
                                <?php if ($item->external_moderators != null) : ?>
                                    <?php
                                    if (count($item->moderators) != 0) {
                                        echo ", ";
                                    }
                                    ?>
                                    <?= $item->external_moderators; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- MODERATORS end -->
                        
                        <!-- PROTOCOL start -->
                        <?php if ($item->notes != null) : ?>
                            <tr>
                                <td style="vertical-align: top;">
                                    <strong><?= Yii::t('MeetingModule.views_index_index', 'Protocol'); ?></strong>:
                                </td>
                                <td style="vertical-align: top;"><?= \humhub\widgets\MarkdownView::widget(array('markdown' => $item->notes)); ?></td>
                            </tr>
                        <?php endif; ?>
                        <!-- PROTOCOL end -->

                        <!-- TASKS start -->
                        <?php if ($contentContainer->isModuleEnabled('tasks') && !empty($item->meetingTasks)) : ?>
                            <tr>
                                <td style="vertical-align: top;"><strong><?= Yii::t('MeetingModule.views_index_index', 'Tasks'); ?></strong>:</td>
                                <td style="vertical-align: top;">
                                    <?php foreach ($item->meetingTasks as $task): ?>

                                        <?php /* @var $task \humhub\modules\meeting\models\MeetingTask */?>

                                        <?php if(!$task->task) {continue;} // Just to get sure there are no integrity issues ?>

                                        <?php if (!$task->isCompleted()) : ?>
                                            <i class="fa fa-square-o"> </i>
                                        <?php else: ?>
                                            <i class="fa fa-check-square-o"> </i>
                                        <?php endif; ?>
                                            
                                        &nbsp;<?= Html::encode($task->getTitle()); ?>
                                        
                                        <?php if ($task->hasScheduling()) : ?>
                                            (
                                            <span class="<?=  ($task->isOverdue()) ? "label label-danger" : ''; ?>"><?= Yii::$app->formatter->asDate(new DateTime($task->getEndDate()), 'short'); ?></span>
                                            )

                                        <?php endif; ?>
                                        <?php $assignedUsers = $task->assignedUsers; ?>

                                        <?php if (!empty($assignedUsers)) : ?>
                                            &nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i>&nbsp;
                                            <?=
                                            implode(", ", array_map(function ($p) {
                                                        return Html::encode($p->displayName);
                                                    }, $assignedUsers));
                                            ?>

                                        <?php endif; ?><br>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <!-- TASKS end -->
                    </table>
                    <!-- Meeting Infos end -->
                    
                </div>
                <div class="print-body">
                <hr>
                </div>
<?php endforeach; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();
</script>
