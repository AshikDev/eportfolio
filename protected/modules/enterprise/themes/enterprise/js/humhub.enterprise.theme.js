humhub.module('enterprise.theme', function (module, require, $) {
    var event = require('event');
    var object = require('util').object;
    var additions = require('ui.additions');

    event.on('humhub:modules:space:chooser:beforeInit', function (evt, spaceChooser) {
        var SpaceChooser = spaceChooser.SpaceChooser;

        SpaceChooser.prototype.init = function () {
            this.$menu = $('#space-menu-dropdown');
            this.$chooser = $('#space-menu-remote-search');
            this.$search = $('#space-menu-search');
            this.$remoteSearch = $('#space-menu-remote-search');

            this.initEvents();
            this.initSpaceSearch();
        };

        SpaceChooser.prototype.setSpace = function (space) {
            this.getItems().removeClass('active');
            this.findItem(space).addClass('active');
            this.setSpaceMessageCount(space, 0);
        };

        SpaceChooser.prototype.setNoSpace = function () {
            this.getItems().removeClass('active');
        };

        SpaceChooser.prototype.getFirstItem = function () {
            return this.$.find('[data-space-chooser-item]:visible').first();
        };

        SpaceChooser.prototype.prependItem = function (space) {
            if (!this.findItem(space).length) {
                var $space = $(space.output);
                var spaceTypeId = $space.data('space-type');
                var $menu = (object.isNumber(spaceTypeId)) ? $('#space-menu-type-' + spaceTypeId) : this.$chooser;

                if (!$menu.length) {
                    $menu = this.$chooser;
                }

                $menu.prepend($space);
                additions.applyTo($space);
            }
        };

        SpaceChooser.prototype.appendItem = function (space) {
            if (!this.findItem(space).length) {
                var $space = $(space.output);
                var spaceTypeId = $space.data('space-type');
                var $menu = (object.isNumber(spaceTypeId)) ? $('#space-menu-type-' + spaceTypeId) : this.$chooser;

                if (!$menu.length) {
                    $menu = this.$chooser;
                }

                $menu.append($space);
                additions.applyTo($space);
            }
        };

        SpaceChooser.prototype.clearRemoteSearch = function (input) {
            // Clear all non member and non following spaces
            this.$.find('[data-space-none],[data-space-archived]').each(function () {
                var $this = $(this);
                if (!input || !input.length || $this.find('.space-name').text().toLowerCase().search(input) < 0) {
                    $this.remove();
                }
            });
        };
    });

    module.initOnPjaxLoad = true;

    var init = function (pjax) {
        _closeMenu();

        setTimeout(_alignSpaceNavTop, 50);

        $( window ).resize(function() {
            _alignSpaceNavTop();
        });

        //_alignSpaceNavTop();
        if (!pjax) {
            _initTopMenuToggle();
            _initSpaceTypeNav();
        }
    };

    var _initSpaceTypeNav = function () {
        var mq = window.matchMedia("(max-width: 768px)");

        if (!mq.matches) {
            _addNiceScroll();

        }

        $('.space-type-nav-title').on('click', function () {
            var $this = $(this);
            $this.next('li').find('.space-type-nav-container').slideToggle('fast');
            $this.find('i').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
        });

        $('.title-link').on('click', function (evt) {
            // prevent trigger of space-type-nav-title
            evt.stopPropagation();
        });
    };

    var _alignSpaceNavTop = function () {
        var navHeight = $('.space-nav:first').height();
        $('.space-layout-container').css('margin-top', navHeight + 2);
    };

    var _addNiceScroll = function () {
        $("#sidebar-wrapper").niceScroll({
            cursorwidth: "7",
            cursorborder: "",
            cursorcolor: "#606572",
            cursoropacitymax: "0.3",
            nativeparentscrolling: false,
            railpadding: {top: 0, right: 3, left: 0, bottom: 0}
        });
    };

    var _removeNiceScroll = function () {
        $("#sidebar-wrapper").getNiceScroll().remove();
    };

    var _resizeNiceScroll = function () {
        $("#sidebar-wrapper").getNiceScroll().resize();
    };

    var _initTopMenuToggle = function () {
        var mq = window.matchMedia("(max-width: 768px)");

        $(".menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");

            if ($('#wrapper').css('padding-left') == "250px") {
                $('#rp-nav').css('display', 'block');
                $('#topbar-first').css('padding-left', '0');

                if (mq.matches) {
                    $('#topbar-first div').removeClass('hidden');
                    $('.space-nav .nav').removeClass('hidden');
                    $('#rsp-backdrop').remove();
                } else {
                    _removeNiceScroll();
                }

            } else {
                $('#rp-nav').css('display', 'none');
                $('#topbar-first').css('padding-left', '250px');

                if (mq.matches) {
                    $('#topbar-first div').addClass('hidden');
                    $('.space-nav .nav').addClass('hidden');

                    $('#page-content-wrapper').append('<div id="rsp-backdrop" class="modal-backdrop in" style="z-index: 940;"></div>');
                } else {
                    _addNiceScroll();
                    setTimeout(_resizeNiceScroll, 300)
                }
            }
        });
    };

    var _closeMenu = function () {
        var mq = window.matchMedia("(max-width: 768px)");

        $("#wrapper").removeClass("toggled");


        $('#rp-nav').css('display', 'block');
        $('#topbar-first').css('padding-left', '0');

        if (mq.matches) {
            $('#topbar-first div').removeClass('hidden');
            $('.space-nav .nav').removeClass('hidden');
            $('#rsp-backdrop').remove();
        }
    };

    module.export({
        init: init
    });
});

