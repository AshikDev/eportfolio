/*
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

var vCardDelayTimer;


$(document).on('mouseenter', '[data-contentcontainer-id]', function () {
    var trigger = this;
    var contentContainerId = $(trigger).data('contentcontainer-id');
    var divId = 'vCard' + contentContainerId;
    clearTimeout(vCardDelayTimer);
    vCardDelayTimer = setTimeout(function () {
        //console.log("Delay done");
        if (!$("#" + divId).length) {
            //console.log("Create and load VCard");
            $("body").append('<div id="' + divId + '" class="hidden"></div>');
            $.ajax({
                type: 'POST',
                url: vCardLoadUrl,
                data: {'contentContainerId': contentContainerId},
                success: function (response) {
                    //console.log("Loading done");
                    $("#" + divId).html(response);

                    if (response != '') {
                        showPopOver(trigger, response);
                    }
                }
            });
        } else {
            //console.log("Use cached");
            showPopOver(trigger, $("#" + divId).html());
        }
    }, vCardDelay);
});


$(document).on('mouseleave', '[data-contentcontainer-id]', function () {
    clearTimeout(vCardDelayTimer);

    var that = this;
    setTimeout(function () {
        if (!$('.popover:hover').length) {
            $(that).popover('hide');
        }
    }, 300);

});

function showPopOver(trigger, content) {
    $(trigger).popover({
        trigger: 'manual',
        html: true,
        placement: 'auto left',
        container: 'body',
        content: content,
        animation: true
    });

    $(trigger).popover('show');
    $('.popover').on('mouseleave', function () {
        $(trigger).popover('hide');
    });
}