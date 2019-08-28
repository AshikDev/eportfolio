<?php

use humhub\modules\space\modules\manage\widgets\DefaultMenu;

$this->registerJsFile('@web-static/resources/space/spaceHeaderImageUpload.js');
$this->registerJsVar('profileImageUploaderUrl', $space->createUrl('/space/manage/image/upload'));
$this->registerJsVar('profileHeaderUploaderUrl', $space->createUrl('/space/manage/image/banner-upload'));
?>


<br/>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('EnterpriseModule.spacetype', '<strong>Change</strong> space image'); ?></div>
    <?= DefaultMenu::widget(['space' => $space]); ?>
    <div class="panel-body">

        <strong><?php echo Yii::t('EnterpriseModule.spacetype', 'Current image'); ?></strong>

        <div class="image-upload-container profile-user-photo-container"
             style="width: 140px; height: 140px; margin-top: 5px;">

            <?php
            /* Get original profile image URL */

            $profileImageExt = pathinfo($space->getProfileImage()->getUrl(), PATHINFO_EXTENSION);

            $profileImageOrig = preg_replace('/.[^.]*$/', '', $space->getProfileImage()->getUrl());
            $defaultImage = (basename($space->getProfileImage()->getUrl()) == 'default_space.jpg' || basename($space->getProfileImage()->getUrl()) == 'default_space.jpg?cacheId=0') ? true : false;
            $profileImageOrig = $profileImageOrig . '_org.' . $profileImageExt;

            if (!$defaultImage) {
                ?>
                <!-- profile image output-->
                <a data-toggle="lightbox" data-gallery="" href="<?php echo $profileImageOrig; ?>#.jpeg"
                   data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('SpaceModule.widgets_views_profileHeader', 'Close'); ?></button>'>
                       <?php echo \humhub\modules\space\widgets\Image::widget(['space' => $space, 'width' => 140]); ?>
                </a>
            <?php } else { ?>
                <?php echo \humhub\modules\space\widgets\Image::widget(['space' => $space, 'width' => 140]); ?>
            <?php } ?>

            <form class="fileupload" id="profilefileupload" action="" method="POST" enctype="multipart/form-data"
                  style="position: absolute; top: 0; left: 0; opacity: 0; height: 140px; width: 140px;">
                <input type="file" name="spacefiles[]">
            </form>

            <div class="image-upload-loader" id="profile-image-upload-loader" style="padding-top: 60px;">
                <div class="progress image-upload-progess-bar" id="profile-image-upload-bar">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="00"
                         aria-valuemin="0"
                         aria-valuemax="100" style="width: 0%;">
                    </div>
                </div>
            </div>

        </div>
        <br>
        <div>

            <a href="#" onclick="javascript:$('#profilefileupload input').click();" class="btn btn-primary"><i
                    class="fa fa-cloud-upload"></i> <?= Yii::t('EnterpriseModule.spacetype', 'Upload image'); ?></a>
            <a id="profile-image-upload-edit-button"
               style="<?php
               if (!$space->getProfileImage()->hasImage()) {
                   echo 'display: none;';
               }
               ?>"
               href="<?php echo $space->createUrl('/space/manage/image/crop'); ?>"
               class="btn btn-primary" data-target="#globalModal"><i
                    class="fa fa-edit"></i> <?= Yii::t('EnterpriseModule.spacetype', 'Crop image'); ?></a>
                <?php
                    echo humhub\widgets\ModalConfirm::widget(array(
                        'uniqueID' => 'modal_profileimagedelete',
                    'linkOutput' => 'a',
                    'title' => Yii::t('SpaceModule.widgets_views_deleteImage', '<strong>Confirm</strong> image deleting'),
                    'message' => Yii::t('SpaceModule.widgets_views_deleteImage', 'Do you really want to delete your profile image?'),
                    'buttonTrue' => Yii::t('SpaceModule.widgets_views_deleteImage', 'Delete'),
                    'buttonFalse' => Yii::t('SpaceModule.widgets_views_deleteImage', 'Cancel'),
                    'linkContent' => '<i class="fa fa-times"></i>&nbsp;' . Yii::t('EnterpriseModule.spacetype', 'Delete image'),
                    'cssClass' => 'btn btn-danger pull-right',
                    'style' => $space->getProfileImage()->hasImage() ? '' : 'display: none;',
                    'linkHref' => $space->createUrl("/space/manage/image/delete", array('type' => 'profile')),
                    'confirmJS' => 'function(jsonResp) { resetProfileImage(jsonResp); }'
                ));
                ?>
        </div>
    </div>


</div>


