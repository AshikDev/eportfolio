<?php
/* @var $this \yii\web\View */
/* @var $keyword string */
/* @var $spaces humhub\modules\space\models\Space[] */

/* @var $pagination yii\data\Pagination */

use humhub\libs\Helpers;
use humhub\libs\Html;
use humhub\modules\directory\widgets\SpaceTagList;
use humhub\modules\space\widgets\FollowButton;
use humhub\modules\space\widgets\Image;
use humhub\widgets\LinkPager;
use yii\helpers\Url;

?>
<div class="panel panel-default">

    <div class="panel-heading">
        <?= Yii::t('DirectoryModule.base', '<strong>Hub</strong> directory'); ?>
    </div>

    <div class="panel-body">
        <?= Html::beginForm(Url::to(['/directory/directory/spaces']), 'get', ['class' => 'form-search']); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group form-group-search">
                    <?= Html::textInput('keyword', $keyword, ['class' => 'form-control form-search', 'placeholder' => Yii::t('DirectoryModule.base', 'search for spaces')]); ?>
                    <?= Html::submitButton(Yii::t('DirectoryModule.base', 'Search'), ['class' => 'btn btn-default btn-sm form-button-search']); ?>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <?= Html::endForm(); ?>

        <?php if (count($spaces) == 0): ?>
            <p><?= Yii::t('DirectoryModule.base', 'No spaces found!'); ?></p>
        <?php endif; ?>
    </div>

    <hr>
    <ul class="media-list">
        <?php if($keyword == ''): ?>
        <?php foreach ($spaces as $space) : ?>
        <?php  if ($space->community == '_0_'): ?>
                <li>
                    <div class="media">
                        <div class="pull-right">
                            <?=
                            FollowButton::widget([
                                'space' => $space,
                                'followOptions' => ['class' => 'btn btn-primary btn-sm'],
                                'unfollowOptions' => ['class' => 'btn btn-info btn-sm']
                            ]);
                            ?>
                        </div>

                        <?= Image::widget([
                            'space' => $space, 'width' => 50,
                            'htmlOptions' => [
                                'class' => 'media-object',
                                'data-contentcontainer-id' => $space->contentcontainer_id
                            ],
                            'linkOptions' => ['class' => 'pull-left'],
                            'link' => true,
                        ]); ?>

                        <?php if ($space->isMember()): ?>
                            <i class="fa fa-user space-member-sign tt" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="<?= Yii::t('DirectoryModule.base', 'You are a member of this space'); ?>"></i>
                        <?php endif; ?>

                        <div class="media-body">
                            <h4 class="media-heading">
                                <?= Html::containerLink($space); ?>
                                <?php if ($space->isArchived()) : ?>
                                    <span
                                            class="label label-warning"><?= Yii::t('ContentModule.widgets_views_label', 'Archived'); ?></span>
                                <?php endif; ?>
                            </h4>

                            <h5><?= Html::encode(Helpers::truncateText($space->description, 100)); ?></h5>
                            <?= SpaceTagList::widget(['space' => $space]); ?>
                        </div>
                    </div>
                </li>
        <?php foreach ($spaces as $s): ?>
                <?php if (strpos($s->community, '_' . $space->id . '_') !== false): ?>
            <li>
                <div class="media" style="padding-left: 40px;">
                    <div class="pull-right">
                        <?=
                        FollowButton::widget([
                            'space' => $s,
                            'followOptions' => ['class' => 'btn btn-primary btn-sm'],
                            'unfollowOptions' => ['class' => 'btn btn-info btn-sm']
                        ]);
                        ?>
                    </div>

                    <?= Image::widget([
                        'space' => $s, 'width' => 50,
                        'htmlOptions' => [
                            'class' => 'media-object',
                            'data-contentcontainer-id' => $s->contentcontainer_id
                        ],
                        'linkOptions' => ['class' => 'pull-left'],
                        'link' => true,
                    ]); ?>

                    <?php if ($s->isMember()): ?>
                        <i class="fa fa-user space-member-sign tt" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="<?= Yii::t('DirectoryModule.base', 'You are a member of this space'); ?>"></i>
                    <?php endif; ?>

                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= Html::containerLink($s); ?>
                            <?php if ($s->isArchived()) : ?>
                                <span
                                    class="label label-warning"><?= Yii::t('ContentModule.widgets_views_label', 'Archived'); ?></span>
                            <?php endif; ?>
                        </h4>

                        <h5><?= Html::encode(Helpers::truncateText($s->description, 100)); ?></h5>
                        <?= SpaceTagList::widget(['space' => $s]); ?>
                    </div>
                </div>
            </li>
                    <?php endif; ?>
                <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php else: ?>
        <?php foreach ($spaces as $space) : ?>
            <li>
                <div class="media">
                    <div class="pull-right">
                        <?=
                        FollowButton::widget([
                            'space' => $space,
                            'followOptions' => ['class' => 'btn btn-primary btn-sm'],
                            'unfollowOptions' => ['class' => 'btn btn-info btn-sm']
                        ]);
                        ?>
                    </div>

                    <?= Image::widget([
                        'space' => $space, 'width' => 50,
                        'htmlOptions' => [
                            'class' => 'media-object',
                            'data-contentcontainer-id' => $space->contentcontainer_id
                        ],
                        'linkOptions' => ['class' => 'pull-left'],
                        'link' => true,
                    ]); ?>

                    <?php if ($space->isMember()): ?>
                        <i class="fa fa-user space-member-sign tt" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="<?= Yii::t('DirectoryModule.base', 'You are a member of this space'); ?>"></i>
                    <?php endif; ?>

                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= Html::containerLink($space); ?>
                            <?php if ($space->isArchived()) : ?>
                                <span
                                        class="label label-warning"><?= Yii::t('ContentModule.widgets_views_label', 'Archived'); ?></span>
                            <?php endif; ?>
                        </h4>

                        <h5><?= Html::encode(Helpers::truncateText($space->description, 100)); ?></h5>
                        <?= SpaceTagList::widget(['space' => $space]); ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<div class="pagination-container">
    <?= LinkPager::widget(['pagination' => $pagination]); ?>
</div>
