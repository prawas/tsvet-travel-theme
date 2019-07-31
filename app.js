var Utils = {
    prefix: (function () {
        var elem;
        return function (prop) {
            var prefixes = ['Webkit', 'Moz', 'ms', 'O'],
                upper = prop.charAt(0).toUpperCase() + prop.slice(1);

            if (!elem) {
                elem = document.createElement('div');
            }

            for (var len = prefixes.length; len--;) {
                if ((prefixes[len] + upper) in elem.style) {
                    return (prefixes[len] + upper);
                }
            }

            if (prop in elem.style) {
                return prop;
            }

            return false;
        };
    })(),

    TRANSITION_END: 'customTransitionEnd',
    ANIMATION_END: 'customAnimationEnd',

    reflow: function (element) {
        return element.offsetHeight
    },

    getSelectorFromElement: function (element) {
        var selector = element.getAttribute('data-target');
        if (!selector || selector === '#') {
            selector = element.getAttribute('href') || '';
        }

        try {
            var $selector = jQuery(selector);
            return $selector.length > 0 ? selector : null;
        } catch (error) {
            return null;
        }
    },

    emptyFn: function () {}
};

jQuery.each(['transition', 'animation'], function (index, style) {
    var upper = style.charAt(0).toUpperCase() + style.slice(1);

    Utils[style] = (function () {
        var endEventNames = {};
        endEventNames['Webkit' + upper] = 'webkit' + upper + 'End';
        endEventNames['Moz' + upper] = style + 'end';
        endEventNames['ms' + upper] = 'MS' + upper + 'End';
        endEventNames['O' + upper] = 'o' + upper + 'End';
        endEventNames[style] = style + 'end';

        var prefix = Utils.prefix(style);

        return prefix ? {
            end: endEventNames[prefix]
        } : false;
    })();
});
(function ($) {
    var emulate = function (upper) {
        return function (duration) {
            var that = this;
            var called = false;
            this.one('custom' + upper + 'End', function () {
                called = true
            });
            setTimeout(function () {
                if (!called) {
                    that.trigger('custom' + upper + 'End');
                }
            }, duration);
            return this;
        }
    };
    $.fn.emulateTransitionEnd = emulate('Transition');
    $.fn.emulateAnimationEnd = emulate('Animation');

    var special = function (style) {
        return {
            bindType: Utils[style].end,
            delegateType: Utils[style].end,
            handle: function (event) {
                if ($(event.target).is(this)) {
                    return event.handleObj.handler.apply(this, arguments);
                }
            }
        };
    };
    $.event.special.customTransitionEnd = special('transition');
    $.event.special.customAnimationEnd = special('animation');

    var animateClass = function (style, upper) {
        return function (classes, duration, callback) {
            return this.each(function (index) {
                if (!Utils[style]) {
                    return callback && callback.call(this, index);
                }

                var $this = $(this).addClass(classes)
                    .one('custom' + upper + 'End', function () {
                        var $element = $(this).removeClass(classes);
                        callback && callback.call(this, index, $element);
                    });
                var duration_element = duration;

                if (typeof duration === 'function') {
                    duration_element = duration(index);
                }

                $this['emulate' + upper + 'End'](duration_element);
            });
        }
    };
    $.fn.transitionClass = animateClass('transition', 'Transition');
    $.fn.animationClass = animateClass('animation', 'Animation');
})(jQuery);

(function ($) {
    var $document = $(document);
    var $body = $('body');

    /**
     * ------------------------------------------------------------------------
     * Constants
     * ------------------------------------------------------------------------
     */

    var NAME = 'modal';
    var DATA_KEY = 'modal';
    var EVENT_KEY = '.' + DATA_KEY;
    var DATA_API_KEY = '.data-api';
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var ANIMATION_DURATION = 500;
    var BACKDROP_ANIMATION_DURATION = 500;
    var ESCAPE_KEYCODE = 27; // KeyboardEvent.which value for Escape (Esc) key

    var Default = {
        keyboard: true,
        focus: true,
        show: true,
        ajax: false
    };

    var DefaultType = {
        backdrop: '(boolean|string)',
        keyboard: 'boolean',
        focus: 'boolean',
        show: 'boolean'
    };

    var Event = {
        HIDE: 'hide' + EVENT_KEY,
        HIDDEN: 'hidden' + EVENT_KEY,
        SHOW: 'show' + EVENT_KEY,
        SHOWN: 'shown' + EVENT_KEY,
        FOCUSIN: 'focusin' + EVENT_KEY,
        CLICK_DISMISS: 'click.dismiss' + EVENT_KEY,
        KEYDOWN_DISMISS: 'keydown.dismiss' + EVENT_KEY,
        MOUSEUP_DISMISS: 'mouseup.dismiss' + EVENT_KEY,
        MOUSEDOWN_DISMISS: 'mousedown.dismiss' + EVENT_KEY,
        CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
    };

    var ClassName = {
        MODAL: NAME,
        DIALOG: NAME + '__dialog',
        CLOSE: NAME + '__close',
        HEADER: NAME + '__header',
        BODY: NAME + '__body',
        SCROLLBAR_MEASURER: NAME + '-scrollbar-measure',
        BACKDROP: NAME + '-backdrop',
        OPEN: NAME + '-open',
        MODAL_SHOW: 'customFadeInUpSmall',
        MODAL_HIDE: 'customFadeOutDownSmall',
        BACKDROP_SHOW: 'fadeIn',
        BACKDROP_HIDE: 'fadeOut',
        ANIMATE: 'animated middle-speed'
    };

    var Selector = {
        DIALOG: '.' + ClassName.DIALOG,
        BODY: '.' + ClassName.BODY,
        DATA_TOGGLE: '[data-toggle="' + NAME + '"]',
        DATA_DISMISS: '[data-dismiss="' + NAME + '"]'
    };

    var guid = 0;


    /**
     * ------------------------------------------------------------------------
     * Class Definition
     * ------------------------------------------------------------------------
     */

    var Modal = function (element, config) {
        this._config = $.extend({}, Default, config);
        this._$element = $(element).addClass(ClassName.ANIMATE);
        this._element = element;
        this._$dialog = this._element.$dialog || this._$element.find(Selector.DIALOG);
        this._$close = this._element.$close || this._$dialog.find(Selector.CLOSE);
        this._$body = this._element.$body || this._$dialog.find(Selector.BODY);
        this._$backdrop = null;
        this._backdrop = null;
        this._isShown = false;
        this._ignoreBackdropClick = false;
    };

    // public

    Modal.prototype.toggle = function () {
        return this._isShown ? this.hide() : this.show();
    };
    Modal.prototype.show = function () {
        var that = this;
        if (this._isTransitioning) {
            return;
        }

        if (Utils.animation) {
            this._isTransitioning = true;
        }

        var showEvent = $.Event(Event.SHOW);

        this._$element.trigger(showEvent);

        if (this._isShown || showEvent.isDefaultPrevented()) {
            return;
        }

        this._isShown = true;

        this._setEscapeEvent();

        this._$element.on(Event.CLICK_DISMISS, Selector.DATA_DISMISS, this.hide.bind(this));

        this._$dialog.on(Event.MOUSEDOWN_DISMISS, function () {
            that._$element.one(Event.MOUSEUP_DISMISS, function (event) {
                if ($(event.target).is(that._element)) {
                    that._ignoreBackdropClick = true;
                }
            });
        });

        this._showBackdrop(function () {
            if (that._config.ajax) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    method: 'post',
                    data: {
                        action: 'get_page',
                        url: that._config.ajax
                    },
                    success: function (response) {
                        that._$body.html(response.data[0]);
                        that._showElement();
                        that._config.ajax = false;
                    },
                    error: function () {
                        that._showElement();
                    }
                });
            } else {
                that._showElement();
            }
        });
    };

    Modal.prototype.hide = function (event) {
        var that = this;
        if (event) {
            event.preventDefault();
        }

        if (this._isTransitioning || !this._isShown) {
            return;
        }

        if (Utils.animation) {
            this._isTransitioning = true;
        }

        var hideEvent = $.Event(Event.HIDE);

        this._$element.trigger(hideEvent);

        if (!this._isShown || hideEvent.isDefaultPrevented()) {
            return;
        }

        this._isShown = false;

        this._setEscapeEvent();

        $document.off(Event.FOCUSIN);

        this._$element.off(Event.CLICK_DISMISS);
        this._$dialog.off(Event.MOUSEDOWN_DISMISS);

        this._$element.animationClass(ClassName.MODAL_HIDE, ANIMATION_DURATION, this._hideModal.bind(this));
    };

    // private

    Modal.prototype._showElement = function () {
        var that = this;
        this._element.style.display = 'block';
        this._element.removeAttribute('aria-hidden');

        if (Utils.animation) {
            Utils.reflow(this._element);
        }

        if (this._config.focus) {
            this._enforceFocus();
        }

        var shownEvent = $.Event(Event.SHOWN);

        this._$element.animationClass(ClassName.MODAL_SHOW, ANIMATION_DURATION, function () {
            if (that._config.focus) {
                that._element.focus();
            }
            that._isTransitioning = false;
            that._$element.trigger(shownEvent);
        });
    };

    Modal.prototype._enforceFocus = function () {
        var that = this;
        $document
            .off(Event.FOCUSIN) // guard against infinite focus loop
            .on(Event.FOCUSIN, function (event) {
                if (document !== event.target &&
                    that._element !== event.target &&
                    !that._$element.has(event.target).length) {
                    that._element.focus();
                }
            });
    };

    Modal.prototype._setEscapeEvent = function () {
        var that = this;
        if (this._isShown && this._config.keyboard) {
            this._$element.on(Event.KEYDOWN_DISMISS, function (event) {
                if (event.which === ESCAPE_KEYCODE) {
                    event.preventDefault();
                    that.hide();
                }
            });
        } else if (!this._isShown) {
            this._$element.off(Event.KEYDOWN_DISMISS);
        }
    };

    Modal.prototype._hideModal = function () {
        var that = this;
        this._element.style.display = 'none';
        this._element.setAttribute('aria-hidden', true);
        this._isTransitioning = false;
        this._hideBackdrop(function () {
            that._$element.trigger(Event.HIDDEN);
        });
    };

    Modal.prototype._createBackdrop = function () {
        this._backdrop = document.createElement('div');
        this._backdrop.className = ClassName.BACKDROP;
        this._$backdrop = $(this._backdrop)
            .addClass(ClassName.ANIMATE);

        this._$backdrop.appendTo(document.body);
    };

    Modal.prototype._removeBackdrop = function () {
        if (this._$backdrop !== undefined) {
            this._$backdrop.remove();
            this._$backdrop = null;
        }
    };

    Modal.prototype._showBackdrop = function (callback) {
        var that = this;
        this._createBackdrop();

        this._$element.add(this._$close)
            .on(Event.CLICK_DISMISS, function (event) {
                if (that._ignoreBackdropClick) {
                    that._ignoreBackdropClick = false;
                    return;
                }
                if (!$(this).hasClass(ClassName.CLOSE) && event.target !== event.currentTarget) {
                    return;
                }
                that.hide();
            });

        if (Utils.animation) {
            Utils.reflow(this._backdrop);
        }

        $body.addClass(ClassName.OPEN);
        this._$backdrop.animationClass(ClassName.BACKDROP_SHOW, BACKDROP_ANIMATION_DURATION, callback);
    };

    Modal.prototype._hideBackdrop = function (callback) {
        var that = this;
        $body.removeClass(ClassName.OPEN);
        this._$backdrop.animationClass(ClassName.BACKDROP_HIDE, BACKDROP_ANIMATION_DURATION, function () {
            that._removeBackdrop();
            if (callback) {
                callback();
            }
        });
    };

    // static

    Modal._jQueryInterface = function (config) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data(DATA_KEY);
            var _config = $.extend({}, Default, $this.data(), typeof config === 'object' && config);

            if (!data) {
                data = new Modal(this, _config);
                $this.data(DATA_KEY, data);
            }

            if (typeof config === 'string') {
                if (data[config] === undefined) {
                    throw new Error('No method named "' + config + '"');
                }
                data[config]();
            } else if (_config.show) {
                data.show();
            }
        });
    };

    Modal.createElement = function () {
        var $element = $('<div class="' + ClassName.MODAL + '" style="display: none;"/>');
        var $dialog = $('<section class="' + ClassName.DIALOG + '" />').appendTo($element);
        $body.append($element);

        var element = $element[0];
        element.$close = $('<svg class="' + ClassName.CLOSE + '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><g transform="rotate(45 12.5 12.5) translate(-2.5 -2.5)"><rect width="30" height="4" x="0" y="13"/><rect width="4" height="30" x="13" y="0"/></g></svg>').appendTo($dialog);
        element.$header = $('<header class="' + ClassName.HEADER + '" />').appendTo($dialog);
        element.$body = $('<article class="' + ClassName.BODY + '" />').appendTo($dialog);
        element.$close = element.$close.add(element.$close.clone().addClass(ClassName.CLOSE + '_bottom visible-xs').appendTo($dialog));

        return $element;
    };


    /**
     * ------------------------------------------------------------------------
     * Data Api implementation
     * ------------------------------------------------------------------------
     */

    $document.on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {
        var $target;
        var $this = $(this);
        var selector = Utils.getSelectorFromElement(this);

        if (selector) {
            var target = $(selector)[0];
            $target = $(target);
        } else {
            var modal_id = NAME + '-' + guid++;
            $target = Modal.createElement();
            $target[0].$header.text($this.text());
            $target.attr('id', modal_id);
            $this.attr('data-target', '#' + modal_id);
        }
        var config = $target.data(DATA_KEY) ? 'toggle' : $.extend({}, $target.data(), $this.data());

        $target.one(Event.SHOW, function (showEvent) {
            if (showEvent.isDefaultPrevented()) {
                // only register focus restorer if modal will actually get shown
                return;
            }

            $target.one(Event.HIDDEN, function () {
                if ($(this).is(':visible')) {
                    this.focus();
                }
            });
        });

        if (this.tagName === 'A' || this.tagName === 'AREA') {
            event.preventDefault();
        }

        Modal._jQueryInterface.call($target, config);
        return false;
    });


    /**
     * ------------------------------------------------------------------------
     * jQuery
     * ------------------------------------------------------------------------
     */

    $.fn[NAME] = Modal._jQueryInterface;
    $.fn[NAME].Constructor = Modal;
    $.fn[NAME].noConflict = function () {
        $.fn[NAME] = JQUERY_NO_CONFLICT;
        return Modal._jQueryInterface;
    };
})(jQuery);
(function ($) {
    var pages = {
            get_products: 1
        },
        max_pages = {
            get_products: 0
        };
    $('.wrapper-slider').each(function () {
        var $slider = $(this),
            $wrapper = $slider.children('.bx-wrapper'),
            $bottom = $slider.children('.wrapper-slider__bottom');
        $wrapper.find('.bx-prev').bind('click touchend', function () {
            $bottom.find('.bx-prev').trigger('click');
        });
        $wrapper.find('.bx-next').bind('click touchend', function () {
            $bottom.find('.bx-next').trigger('click');
        });
    });
    $('.bx-prev').bind('click touchend', function () {
        $('.search-form__background .bx-prev').trigger('click');
    });

    var exchange_rate_rub = parseFloat($('.exchange-rates__value_rub').text());
    var exchange_rate_pln = parseFloat($('.exchange-rates__value_pln').text());

    var $section_more_show = $('.section__show-more:not(.section__show-more_next)').on('click', function () {
        var $this = $(this),
            href = $this.attr('href'),
            turoperator = $('[name="turoperator"]:checked').val();
        if ($this.attr('disabled') || !pages[href]) {
            return false;
        }

        if (pages[href].default === undefined) {
            pages[href] = {
                default: pages[href]
            };
            max_pages[href] = {
                default: max_pages[href]
            };
        }
        if (pages[href][turoperator] === undefined) {
            pages[href][turoperator] = pages[href].default;
            max_pages[href][turoperator] = max_pages[href].default;
        }
        if ((max_pages[href][turoperator] > 0 && pages[href][turoperator] > max_pages[href][turoperator])) {
            return false;
        }

        // $this.attr('disabled', 'disabled');
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'post',
            data: {
                action: href,
                page: ++pages[href][turoperator],
                // turoperator: turoperator
            },
            success: function (request) {
                if (!request.success) {
                    return;
                }
                var $section = $this.closest('.section'),
                    $content = $section.find('.section__content'),
                    $append_content = $(request.data['content']);
                if (request.data['selector_container']) {
                    $content = $content.find(request.data['selector_container']);
                    $append_content = $append_content.find(request.data['selector_container']);
                }
                $append_content = $append_content.children();
                if ($append_content.length > 0) {
                    $append_content.find('.woocommerce-Price-amount').each(function () {
                        var $this = $(this),
                            exchange_rate = turoperator === 'Itaka' ? exchange_rate_pln : (turoperator === 'Sletat' ? exchange_rate_rub : 1);
                        $this.html((Math.round(parseFloat($this.text()) * exchange_rate_pln)) + '<span class="woocommerce-Price-currencySymbol">руб</span>');
                    });
                    $content.append($append_content);
                } else {
                    max_pages[href][turoperator] = pages[href][turoperator] - 1;
                }
                $this.removeAttr('disabled');
            },
            error: function (error) {
                $this.removeAttr('disabled');
            }
        });
        return false;
    });

    $('form').on('submit', function () {
        return true;
        var $this = $(this),
            data = $this.serializeArray(),
            $fields = $this.find('input, select').attr('disabled', 'disabled');
        data.push({
            name: 'action',
            value: $this.attr('action')
        });
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: $this.attr('method'),
            data: data,
            success: function (request) {
                if (!request.success) {
                    return;
                }

                $fields.removeAttr('disabled');
            },
            error: function () {
                $fields.removeAttr('disabled');
            }
        });
        return false;
    }).find('input, select').on('change', function () {
        // $(this.form).trigger('submit');
    });

    if (window.location.pathname.indexOf('/itaka/') > -1 || window.location.pathname.indexOf('/sletat/') > -1) {
        $('[data-js-value="ymaxPriceItakaHit"], [data-js-value="offerPrice"], [data-js-value="totalPrice"]').each(function (index) {
            var $this = $(this),
                price = parseInt($this.html().replace('&nbsp;', '')),
                exchange_rate = window.location.pathname.indexOf('/itaka/') > -1 ? exchange_rate_pln : exchange_rate_rub;

            $this.text(Math.round(price * exchange_rate))
                .next().text('руб');
            $('.form-bron__params [name="price-not-sale"], .form-bron__params [name="price"], .form-bron__params [name="price-total"]')
                .eq(index).val(Math.round(price * exchange_rate));
        });
    }
    var is_close_dropdown = true;
    $(document).on('mousedown', function (event) {
        if ($(event.target).closest('.month, select').length > 0) {
            is_close_dropdown = false;
        }
    });
    $('#duration-select').on('change', function () {
        is_close_dropdown = false;
    });

    function updatePrice(result_price) {
        var $percentage = $('[data-js-value="percentItakaHit"]'),
            $result_price = $('[data-js-value="ymaxPriceItakaHit"]'),
            $outer_price = $('[data-js-value="offerPrice"]');
        result_price = result_price || parseFloat(($percentage.length ? $result_price : $outer_price).text());

        if ($percentage.length > 0) {
            var percentage = parseFloat($percentage.data('value'));
            $result_price.text(Math.round(result_price));
            $('.form-bron__params [name="price-not-sale"]').val(Math.round(result_price));
            result_price = Math.round(result_price * (1 - percentage / 100));
        }
        $outer_price.text(result_price);
        $('.form-bron__params [name="price"]').val(result_price);
        var $persons = $('#participants-count'),
            adults = parseInt($persons.data('adults')),
            childs = parseInt($persons.data('childs'));
        $('[data-js-value="totalPrice"]').text(Math.round(result_price * (adults + childs)));
        $('.form-bron__params [name="price-total"]').val(Math.round(result_price * (adults + childs)));
    }
    $('.form-bron .dropdown-menu a').on('click', function () {
        var $this = $(this),
            $dropdown_menu = $this.closest('.dropdown-menu'),
            $dropdown = $dropdown_menu.closest('.dropdown'),
            $label = $dropdown_menu.prevAll('.dropdown-toggle'),
            $input = $dropdown.prevAll('label').find('select');

        //         $price = $(this).find('.extraPrice, .pricesmall'),

        //         $percentage = $('[data-js-value="percentItakaHit"]'),
        //         $result_price = $('[data-js-value="ymaxPriceItakaHit"]'),
        //         $outer_price = $('[data-js-value="offerPrice"]'),
        //         result_price = parseFloat(($percentage.length ? $result_price : $outer_price).text())

        // if($price.length > 0) {
        //     var matched = $price.html().match(/[\+\-]?\format: 'MM/YYYY'd+\.?\d+/);
        //     if (matched) {
        //         result_price += parseFloat(matched[0]);
        //     }
        // }

        var label = $input.find('option').removeAttr('selected')
            .filter('option[value="' + $this.data('value') + '"]').attr('selected', 'selected')
            .text();
        $label.text(label);
        $input.trigger('change');
        //
        $dropdown.find('li.selected').each(function () {
            //         $price = $(this).find('.extraPrice, .pricesmall');
            //         if($price.length > 0) {
            //             var matched = $price.html().match(/[\+\-]?\d+\.?\d+/);
            //             if (matched) {
            //                 result_price -= parseFloat(matched[0]);
            //             }
            //         }
        }).removeClass('selected');
        $this.closest('li').addClass('selected');
        //
        //     updatePrice(result_price);
    });
    $('#childs-select').on('change', function () {
        $('#participants-count').data('childs', $(this).val());
        // updatePrice();
    });
    $(document)
        .on('hide.bs.dropdown', function (event) {
            if (!is_close_dropdown) {
                event.preventDefault();
                is_close_dropdown = true;
            }
        });

    $('.feature').hover(function () {
        $(this).prev().css('opacity', 1);
    }, function () {
        $(this).prev().css('opacity', '');
    });

    var $section_gallery,
        createSectionGallery = function () {
            $section_gallery = $('<div class="section-gallery" />').hide();
            $section_gallery.$preloader = $('<div class="section-gallery__preloader preloader" />').appendTo($section_gallery);
            $section_gallery.$container = $('<div class="section-gallery__container" />').hide().appendTo($section_gallery);

            $section_gallery.$close_btn = $('<div class="section-gallery__close section-gallery__close_begin" />').appendTo($section_gallery.$container);
            $section_gallery.$image = $('<img class="section-gallery__image"/>').appendTo($section_gallery.$container);
            $section_gallery.$title = $('<div class="section-gallery__title"/>').appendTo($section_gallery.$container);
            $section_gallery.$close_btn = $section_gallery.$close_btn.add($section_gallery.$close_btn.clone()
                .removeClass('section-gallery__close_begin')
                .addClass('section-gallery__close_end')
                .appendTo($section_gallery.$container));

            $section_gallery.appendTo('body');

            $section_gallery.add($section_gallery.$close_btn).on('click', function (event) {
                if (event.target == this) {
                    $section_gallery.fadeOut(200, function () {
                        $section_gallery.$container.hide();
                    });
                }
            });
        };
    $('.section__gallery img').on('click', function () {
        var $img = $(this),
            src = $img.attr('src'),
            new_src = src.replace(/\-\d+x\d+\.(jpg|png)$/, ".$1"),
            title = $img.attr('title');
        if (!$section_gallery) {
            createSectionGallery();
        }
        if (src !== new_src) {
            $section_gallery.$image.on('load', function () {
                $section_gallery.$container.fadeIn(200);
            });
        } else {
            $section_gallery.$container.show();
        }
        $section_gallery.$title.text(title || '');
        $section_gallery.$image.attr('src', new_src);
        $section_gallery.fadeIn(200);
    });
    $('body').on('error', 'img', function () {
        var $img = $(this);
        var imagesData = $img.nextAll('.hotel-list-gallery').data('search-gallery');
        var imagesList = imagesData.split(",");
        var src = $img.data('original');
        var pos = imagesList.indexOf(src.replace(/https?:/, ""));
        if (pos > -1 && imagesList.length > pos + 1) {
            src = imagesList[pos + 1].replace(/https?:/, "");
            $img.data('original', 'https:' + src).trigger('appear');
        } else if (pos === -1) {
            src = imagesList[0].replace(/https?:/, "");
            $img.data('original', 'https:' + src).trigger('appear');
        }
    });
    var $departure = $('[name="departure"]'),
        $destination = $('[name="destination"]'),
        $warshawa_departure_itaka = $departure.find('[data-turoperator="itaka"] option')
        .filter(function (index, option) {
            var text = $(option).text().toLowerCase();
            return text.indexOf('wars') > -1 || text.indexOf('варша') > -1;
        })
        .first(),
        $warshawa_departure_tsvet = $departure.find('[data-turoperator="tsvet"] option')
        .filter(function (index, option) {
            var text = $(option).text().toLowerCase();
            return text.indexOf('wars') > -1 || text.indexOf('варша') > -1;
        })
        .first(),
        dep_default_values = {
            Itaka: $warshawa_departure_itaka.length > 0 ? $warshawa_departure_itaka.val() : 'WAW,WZZ',
            Sletat: 832,
            Tsvet: ''
        },
        $turoperator = $('[name="turoperator"]').on('change', function () {
            var turoperator = $(this).val(),
                href = $section_more_show.attr('href');
            $departure.val(dep_default_values[turoperator]).trigger('change');

            if (pages[href].default === undefined) {
                pages[href] = {
                    default: pages[href]
                };
                max_pages[href] = {
                    default: max_pages[href]
                };
            }
            // pages[href][turoperator] = pages[href].default - 1;

            // $section_more_show.closest('.section').find('.section__content .products').empty();
            $section_more_show.trigger('click');
        });
    $departure.add($destination).each(function () {
        var select2 = $(this).data('select2');
        if (select2) {
            select2.on('results:all', function () {
                this.$results
                    .children()
                    .addClass('hidden')
                    .eq(({
                        'Sletat': 1,
                        'Itaka': 2,
                        'Tsvet': 3
                    } [$turoperator.filter(':checked').val()] || 3) - 1)
                    .removeClass('hidden');
            });
        }
    });
    var is_comlete = false;
    $(window).on('resize', function () {
        var $last_element;
        $('.fRow').each(function () {
            var $row = $(this);
            $row.removeClass('fRow_first fRow_last');
            if (!$last_element || $row.offset().top !== $last_element.offset().top) {
                $row.addClass('fRow_first');
                if ($last_element) {
                    $last_element.addClass('fRow_last');
                    is_comlete = true;
                }
            }
            $last_element = $row;
        }).last().addClass('fRow_last');
        if (!$last_element) {
            is_comlete = true;
        }
    }).on('load', function () {
        var fn_interval = setInterval(function () {
            if (is_comlete) {
                clearInterval(fn_interval);
                return;
            }
            $(window).trigger('resize');
        }, 50);
        var $search_form = $('.main > #search-form');
        if ($search_form.length > 0) {
            $('html, body').animate({
                scrollTop: $search_form.offset().top + $search_form.outerHeight()
            }, 200)
        }
    });
    $('.product__thumbnail').each(function (index, image) {
        var $image = $(image);
        var $canvas = $('<canvas class="product__thumbnail" width="' + $image.attr('width') + '" height="' + $image.attr('height') + '" />');
        $image.after($canvas);
        (function (image, canvas) {
            setTimeout(function () {
                StackBlur.image(image, canvas, 5);
            }, 3000);
        })(image, $canvas[0]);
    });
    var $destination_select = $('#destination-select-popup');
    var $destination_options = $destination_select.find('option');
    var $selected_options = $destination_options.filter(':selected');
    $destination_select.on('change', function () {
        var $new_selected_options = $destination_options.filter(':selected');
        if ($new_selected_options.length > $selected_options.length) {
            var $selected_option = $new_selected_options.not($selected_options);
            var $parent = $selected_option;
            var $cities_country = $selected_option.nextUntil('[data-class="option-parent"]');
            if ($selected_option.data('class') === "option-child") {
                $cities_country = $selected_option.add($selected_option.prevUntil('[data-class="option-parent"]'));
                $parent = $cities_country.first().prev();
            }

            $new_selected_options = $cities_country.add($parent);
            $selected_options.not($new_selected_options).prop('selected', false);
            $parent.prop('selected', true);

            $selected_options = $new_selected_options.filter(':selected');
        }
    });
    $('.well input').change(function () {
        if ($('.bron-tour').css('display') === 'none') {
            var $this = $(this),
                $input = $('.bron-tour [name="' + $this.attr('name') + '"]');
            if ($input.length > 0) {
                $input.val($this.val());
            }
        }
    });
    $('.well select').change(function (event, is_change) {
        if (is_change === false) {
            return;
        }
        var $this = $(this),
            $select = $('.bron-tour [name="' + $this.attr('name') + '"]');
        if ($select.length > 0) {
            $select.find('option')
                .removeAttr('selected')
                .prop('selected', false)
                .filter('[value="' + $this.val() + '"]')
                .prop('selected', true)
                .attr('selected', 'selected')
                .trigger('change', false);
        }
    });
    $('[data-js-value="reservationUrl"]').off('click')
        .on('click', function (event) {
            $('.bron-tour').css('display', 'flex');
            $('html, body').css('overflow', 'hidden');
            if ($(window).width() >= 768) {
                $('body').css('padding-right', '17px');
            }
            return false;
        });
    $('.bron-tour').on('click', function (event) {
        if (event.target == this) {
            $('.bron-tour').hide();
            $('html, body').css('overflow', '');
            $('body').css('padding-right', '');
            return false;
        }
    });
    $('.bron-tour select').change(function (event, is_change) {
        if (is_change === false) {
            return;
        }

        var $this = $(this),
            $select = $('.well [name="' + $this.attr('name') + '"]');
        if ($select.length > 0) {
            $select.find('option').removeAttr('selected')
                .prop('selected', false)
                .filter('[value="' + $this.val() + '"]')
                .prop('selected', true)
                .attr('selected', 'selected')
                .trigger('change', false);
        }
    });

    if (window.location.pathname.indexOf('/itaka/') > -1) {
        $('#search-form').on('change', function () {
            var data = $(this).serializeArray();
            [].push.apply(data, [{
                    name: 'action',
                    value: 'itaka_query'
                },
                {
                    name: 'url',
                    value: 'strony'
                },
                {
                    name: '_page',
                    value: '512'
                }
            ]);
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                method: "get",
                data: data,
                dataType: 'json',
                success: function (result) {
                    var price = {
                        ymaxPriceItakaHit: parseFloat(result.data.ymaxPriceItakaHit.replace("&nbsp;", "")),
                        offerPrice: parseFloat(result.data.offerPrice.replace("&nbsp;", "")),
                        totalPrice: parseFloat(result.data.totalPrice.replace("&nbsp;", ""))
                    };
                    $('[data-js-value="ymaxPriceItakaHit"]').text(Math.round(price.ymaxPriceItakaHit * exchange_rate_pln));
                    $('[data-js-value="offerPrice"]').text(Math.round(price.offerPrice * exchange_rate_pln));
                    $('[data-js-value="totalPrice"]').text(Math.round(price.totalPrice * exchange_rate_pln));

                    $('.form-bron__params [name="price-not-sale"]').val(Math.round(price.ymaxPriceItakaHit * exchange_rate_pln));
                    $('.form-bron__params [name="price"]').val(Math.round(price.offerPrice * exchange_rate_pln));
                    $('.form-bron__params [name="price-total"]').val(Math.round(price.totalPrice * exchange_rate_pln));
                }
            })
        }).trigger('change');
    } else if (window.location.pathname.indexOf('/sletat/') > -1) {

    } else {
        $('.well form').on('change', '#adults-select, #childs-select, [name="child_age[]"]', function () {
            var $total_price = $('.well [data-js-value="totalPrice"]');
            var count_adults = $('#adults-select').val();
            var today = new Date();
            var childs_ages = [].map.call($('.well #childs-ages:visible [name="child_age[]"]'), function (age_input) {
                var birth_date = $(age_input).val().split('.');
                birth_date = new Date(birth_date[2], birth_date[1] - 1, birth_date[0]);
                var age = today.getFullYear() - birth_date.getFullYear();
                var m = today.getMonth() - birth_date.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birth_date.getDate())) {
                    age--;
                }
                return age;
            });
            var price_adults = parseFloat($('.well [data-js-value="offerPrice"]').text());
            var price_childs_6 = parseFloat($total_price.data('childPriceSmall'));
            var price_childs_13 = parseFloat($total_price.data('childPriceBig'));
            var price = price_adults * count_adults;
            childs_ages.forEach(function (age) {
                price += age < 6 ? price_childs_6 : price_childs_13;
            });
            $('[data-js-value="totalPrice"]').text(price);
            $('.form-bron__params [name="price-total"]').val(price);
        });
        $('.bron-tour .fParticipants').addClass('onlyOne');
    }
    $('.form-bron select').each(function () {
        var $select = $(this);
        if ($select.find('option').not('[value=""]').length <= 1) {
            $select.closest('label').nextAll('.dropdown').addClass('onlyOne');
        }
    });
    $('#path .stars').clone().appendTo('.form-bron__title');
    $('body').on('click', '.read-more-list-desc', function () {
        $(this).prev().toggleClass('show');
        return false;
    }).on('click', '.products button.product__button', function () {
        $('.cbh-phone-wrapper .cbh-text').trigger('click')
    });
    $('.ur-menu__link').attr('data-toggle', 'modal')
        .each(function () {
            var $this = $(this);
            $this.attr('data-ajax', decodeURIComponent($this.attr('href')));
        });
    $('#adults-select, #childs-select').on('change', function () {
        $('.bron-tour .fParticipants .dropdown-toggle').text($('#participants-count').text());
    });
    var menu_show = false;
    $('.button-menu').on('click', function () {
        $('.apps').toggleClass('apps_close');
        $('.mobile-menu').toggleClass('mobile-menu_open');
        if (!menu_show) {
            $('body').css('overflow', 'hidden');
        } else {
            $('body').css('overflow', '');
        }
        menu_show = !menu_show;
    });
})(jQuery);