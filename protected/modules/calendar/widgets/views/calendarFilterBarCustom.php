<?php

use humhub\modules\content\components\ActiveQueryContentCustom;
use humhub\modules\calendar\models\CalendarEntry;
use humhub\widgets\Button;
use humhub\widgets\FadeIn;
use kartik\select2\Select2;

?>

<?php FadeIn::begin() ?>
    <div class="row calendar-options">
        <div class="col-md-8">
            <div id="calendar-overview-loader" style="position: absolute;right: 10px;top: 60px;"></div>
            <?= Button::defaultType()->link($configUrl)->right()->icon('fa-cog')->visible($canConfigure) ?>
            <?php if ($showSelectors) : ?>
                <div class="calendar-selectors">
                    <strong style="padding-left:10px;">
                        <?= Yii::t('CalendarModule.views_global_index', '<strong>Select</strong> calendars'); ?>
                    </strong>
                    <br>
                    <div style="display:inline-block; float:left;margin-right:10px;">
                        <div class="checkbox">
                            <label class="calendar_my_profile">
                                <input type="checkbox" name="selector" class="selectorCheckbox"
                                       value="<?= ActiveQueryContentCustom::USER_RELATED_SCOPE_OWN_PROFILE; ?>"
                                       <?php if (in_array(ActiveQueryContentCustom::USER_RELATED_SCOPE_OWN_PROFILE, $selectors)): ?>checked="checked"<?php endif; ?>>
                                <?= Yii::t('CalendarModule.views_global_index', 'My profile'); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="calendar_my_spaces">
                                <input id="my_spaces" type="checkbox" name="selector" class="selectorCheckbox"
                                       value="<?= ActiveQueryContentCustom::USER_RELATED_SCOPE_SPACES; ?>"
                                       <?php if (in_array(ActiveQueryContentCustom::USER_RELATED_SCOPE_SPACES, $selectors)): ?>checked="checked"<?php endif; ?>>
                                <?= Yii::t('CalendarModule.views_global_index', 'My Hubs'); ?>
                            </label>
                        </div>
                    </div>
                    <div style="display:inline-block; float:left;margin-right:10px;">
                        <div class="checkbox">
                            <label class="calendar_my_profile">
                                <input id="only_community" type="checkbox" name="selector" class="selectorCheckbox"
                                       value="12345678901"
                                       <?php if (in_array(ActiveQueryContentCustom::USER_RELATED_SCOPE_SPACES, $selectors)): ?>checked="checked"<?php endif; ?>>
                                <?= Yii::t('CalendarModule.views_global_index', 'Only Community'); ?>
                            </label>
                        </div>
                    </div>

                    <?php if (!Yii::$app->getModule('user')->disableFollow) : ?>
                        <div style="display:inline-block;">
                            <div class="checkbox">
                                <label class="calendar_followed_spaces">
                                    <input type="checkbox" name="selector" class="selectorCheckbox"
                                           value="<?= ActiveQueryContentCustom::USER_RELATED_SCOPE_FOLLOWED_SPACES; ?>"
                                           <?php if (in_array(ActiveQueryContentCustom::USER_RELATED_SCOPE_FOLLOWED_SPACES, $selectors)): ?>checked="checked"<?php endif; ?>>
                                    <?= Yii::t('CalendarModule.views_global_index', 'Followed spaces'); ?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label class="calendar_followed_users">
                                    <input type="checkbox" name="selector" class="selectorCheckbox"
                                           value="<?= ActiveQueryContentCustom::USER_RELATED_SCOPE_FOLLOWED_USERS; ?>"
                                           <?php if (in_array(ActiveQueryContentCustom::USER_RELATED_SCOPE_FOLLOWED_USERS, $selectors)): ?>checked="checked"<?php endif; ?>>
                                    <?= Yii::t('CalendarModule.views_global_index', 'Followed users'); ?>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif ?>
            <?php if ($showFilters) : ?>
            <div class="calendar-filters"
                 style="<?= ($showSelectors) ? 'border-left:2px solid ' . $this->theme->variable('default') : '' ?>">
                <strong style="padding-left:10px;">
                    <?= Yii::t('CalendarModule.views_global_index', '<strong>Filter</strong> events'); ?>
                </strong>
                <br>

                <div style="display:inline-block;margin-right:10px;">
                    <div class="checkbox">
                        <label class="calendar_filter_participate">
                            <input type="checkbox" name="filter" class="filterCheckbox"
                                   value="<?= CalendarEntry::FILTER_PARTICIPATE; ?>"
                                   <?php if (in_array(CalendarEntry::FILTER_PARTICIPATE, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?= Yii::t('CalendarModule.views_global_index', 'I\'m attending'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label class="calendar_filter_mine">
                            <input type="checkbox" name="filter" class="filterCheckbox"
                                   value="<?= CalendarEntry::FILTER_MINE; ?>"
                                   <?php if (in_array(CalendarEntry::FILTER_MINE, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?= Yii::t('CalendarModule.views_global_index', 'My events'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!$spaceId) :
            ?>
            <div class="col-md-4" style="text-align: right;">
                <ul style="list-style: none;">
                    <li class="dropdown">
                        <button id="hubs" class="btn btn-secondary dropdown-toggle disabled" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            Please select hubs <b class="caret"></b>
                        </button>
                        <ul class="dropdown-menu custom_menu" style="text-decoration: none">
                            <li style="padding: 10px;">
                                <div class="checkbox">
                                    <label class="calendar_my_profile">
                                        <input type="checkbox" name="hubs" class="selectorCheckbox" id="all_hubs"
                                               value="1234567890" checked="checked" disabled="disabled" /> Select All
                                    </label>
                                </div>
                            </li>
                            <li style="padding: 10px;">
                                <div class="checkbox">
                                    <label class="calendar_my_profile">
                                        <input type="checkbox" name="hubs" class="selectorCheckbox" id="no_hubs"
                                               value="123456789011" /> Deselect All
                                    </label>
                                </div>
                            </li>
                            <?php
                            foreach ($spaceModelAll as $space) :
                                if ($space['community'] == '_0_') :
                                    ?>
                                    <li class="each_community" style="padding: 5px; font-size: 16px;">
                                        <div class="checkbox">
                                            <label class="calendar_my_profile" style="font-weight: bold; ">
                                                <input type="checkbox" name="hubs" class="selectorCheckbox each_hub"
                                                       value="<?= $space['id'] + 1000; ?>"
                                                       checked="checked"/> <?= $space['name']; ?>
                                            </label>
                                        </div>
                                    </li>
                                    <?php
                                    foreach ($spaceModelAll as $s) :
                                        if (strpos($s['community'], '_' . $space['id'] . '_') !== false) :
                                            ?>
                                            <li class="each_space" style="padding-left: 40px;">
                                                <div class="checkbox">
                                                    <label class="calendar_my_profile">
                                                        <input type="checkbox" name="hubs"
                                                               class="selectorCheckbox each_hub"
                                                               value="<?= $s['id'] + 1000; ?>"
                                                               checked="checked"/> <?= $s['name']; ?>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php
                                        endif;
                                    endforeach;
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif ?>
        <?php endif ?>
    </div>
<?php FadeIn::end() ?>

<?php

if (!$spaceId) {
    $this->registerJs("
    
    $(document).on('click', 'ul.custom_menu', function (e) {
      e.stopPropagation();
    });

    if($('#my_spaces').prop('checked') == true){
      $('#hubs').removeClass( 'disabled' );
      $('#only_community').prop('disabled', false);
    } else {
        $('#hubs').addClass( 'disabled' );
        $('#only_community').prop('disabled', true);
    }
    
    $(document).on('change','#my_spaces', function() {
      if($('#my_spaces').prop('checked') == true){
          $('#hubs').removeClass( 'disabled' );
          $('#only_community').prop('disabled', false);
      } else {
          $('#hubs').addClass( 'disabled' );
          $('#only_community').prop('checked', false);
          $('#only_community').prop('disabled', true);
      }

      if ($('#only_community').prop('checked') == true){
          $('.each_space').hide();
      } else {
          $('.each_space').show();
      }         
    });
    
    $('#all_hubs').change(function(){ 
        $('#no_hubs').prop('checked', false);
        $('.each_hub').prop('checked', $(this).prop('checked'));
    });
    
    $('#no_hubs').change(function(){ 
      if ($('#no_hubs').prop('checked') == true){
          $('.each_hub').prop('checked', false);
          $('#all_hubs').prop('checked', false);
      }
    });
    
    if ($('#only_community').prop('checked') == true){
        $('.each_space').hide();
    } else {
        $('.each_space').show();
    }  
    $('#only_community').change(function(){ 
      if ($('#only_community').prop('checked') == true){
          $('.each_space').hide();
      } else {
          $('.each_space').show();
      }  
    });
    
    $('.each_hub').change(function(){                
        if(false == $(this).prop('checked')){
            $('#all_hubs').prop('checked', false);
        }
    
        if ($('.each_hub:checked').length == $('.each_hub').length ){
            $('#all_hubs').prop('checked', true);
        }
        
        if ($('#no_hubs').prop('checked') == true){
           $('#no_hubs').trigger('click');
        }
    });

", \yii\web\View::POS_READY);
}

