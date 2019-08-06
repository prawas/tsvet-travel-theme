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

  // var exchange_rate_rub = parseFloat($('.exchange-rates__value_rub').text());
  // var exchange_rate_pln = parseFloat($('.exchange-rates__value_pln').text());

  var exchange_rate_rub = 0.0305 /* parseFloat($($('.exchange-rates ul li')[3]).text().replace(/[^0-9,.]/g, "").replace(/[,]/g, ".")) */ ;
  var exchange_rate_pln = parseFloat($($('.exchange-rates ul li')[0]).text().replace(/[^0-9,.]/g, "").replace(/[,]/g, "."));

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

  $('.form-bron .dropdown-menu a').on('click', function () {
    var $this = $(this),
      $dropdown_menu = $this.closest('.dropdown-menu'),
      $dropdown = $dropdown_menu.closest('.dropdown'),
      $label = $dropdown_menu.prevAll('.dropdown-toggle'),
      $input = $dropdown.prevAll('label').find('select');

    var label = $input.find('option').removeAttr('selected')
      .filter('option[value="' + $this.data('value') + '"]').attr('selected', 'selected')
      .text();
    $label.text(label);
    $input.trigger('change');
    $dropdown.find('li.selected').each(function () {
    }).removeClass('selected');
    $this.closest('li').addClass('selected');
  });
  $('#childs-select').on('change', function () {
    $('#participants-count').data('childs', $(this).val());
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

(function ($) {
  $('.btn-transfer').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $('#transfer-modal').modal('show');
  });

  $('.search-form label.radio').click(function(e) {
    $(this).find('input').prop('checked', true);
  });

  function pln_changed(e) {
    var $target = $(e.target);
    var course = $target.data('course').replace(',','.');
    var pln = +$target.val();
    $('#curr-calc-byn').val(Number(pln * course).toFixed(2));
  }

  function byn_changed(e) {
    var $target = $(e.target);
    var course = $target.data('course').replace(',','.');
    var byn = +$target.val();
    $('#curr-calc-pln').val(Number(byn / course).toFixed(2));
  }

  $('#curr-calc-pln').keyup(pln_changed);
  $('#curr-calc-pln').change(pln_changed);
  $('#curr-calc-byn').keyup(byn_changed);
  $('#curr-calc-byn').change(byn_changed);
  
})(jQuery);