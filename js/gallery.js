jQuery(function(){
    "use strict";

    window.galleryItaka = function () {

        var $carousel = $('#carousel'),
            $carouselD = $carousel.find('li'),
            $iconFull = $('a.gi-fullscreen'),
            $iconMap = $('a.gi-map'),
            $iconFilm = $('a.gi-film'),
            $isMovie = $('.car-film').length,
            $slider = $('#slider'),
            initFilmVal = false,
            settings = {},
            _that = this;

        function initGallery(settings) {
            initFilm();
            initMagnificPopup();


            bindActions();
            initCarousel(settings);
        }

        function bindActions() {

            $iconFull.on('click', function (e) {
                triggerFullScreen(e, $(this))
            });

            $carouselD.on('dblclick', function (e) {
                triggerFullScreen(e, $(this))
            });


        }

        /* ------------------------------------- Actions ------------------------------- */

        function triggerFullScreen(ev) {

            var $target = $slider.find('.flex-active-slide a');
            // for only one slide
            if ($target.length < 1) {
                $target = $slider.find('li:first a');
            }
            $target.click();
            ev.preventDefault();
        }

        function afterLazyLoad(el) {
            var $image = $(el),
                $imgReal = $('<img/>').attr('src', $image.attr('src')),
                $container = $image.parent().parent(),
                iWidth = $imgReal[0].width,
                iHeight = $imgReal[0].height;

            if (( iWidth / iHeight) <= 2 && $container.width() < iWidth && $(window).width() > 641) {
                $image.css({
                    maxWidth: "100%",
                    position: "relative",
                    top: (Math.floor(($container.height() / 2) - ( ($container.width() / iWidth) * iHeight ) / 2) )
                });
            }
            else {
                $image.css({
                    maxWidth: "100% !important"
                });

            }
        }

        function initCarousel(settings) {
            // must be start together - first carousel then slider
            if ($carouselD.length > 1) {

                $carousel.flexslider({
                    animation: "slide",
                    controlNav: false,
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: 120,
                    itemMargin: 10,
                    touch: true,
                    video: false,
                    asNavFor: '#slider',
                    prevText: "",
                    nextText: "",
                    // after: function (slider) {
                    //     $carousel.find('.flex-viewport li img').trigger('lazyloadIt');
                    // }
                });
            }
            else {
                $carousel.hide();
            }
            $slider.flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                touch: true,
                sync: "#carousel",
                // after: function (slider) {
                //     var $activeSlide = $(slider.context).find('.flex-active-slide');
                //     if ($activeSlide.find('img').length) {
                //         $(slider.context).find('.flex-active-slide img').trigger('lazyloadIt');
                //     }
                // }

            });

            $(window).load(function () {
                $('#slider_mobile').flexslider({
                    animation: "slide",
                    controlNav: false,
                    animationLoop: false,
                    slideshow: false,
                    touch: true,
                    // after: function (slider) {
                    //     var $activeSlide = $(slider.context).find('.flex-active-slide');
                    //     if ($activeSlide.find('img').length) {
                    //         $(slider.context).find('.flex-active-slide img').trigger('lazyloadIt');
                    //     }
                    // }

                });

            });
            //
            // $('#slider img:not(:first), #slider_mobile img:not(:first)').lazyload({
            //     effect: 'fadeIn',
            //     event: 'lazyloadIt',
            //     skip_invisible: false
            // }).load(function () {
            //     afterLazyLoad(this);
            // });

            // $carousel.find('img').lazyload({
            //     effect: 'fadeIn',
            //     event: 'lazyloadIt',
            //     skip_invisible: false
            // });

            $carousel.find('li.empty-li').off();
        }

        function initMagnificPopup() {
            $slider.find(".slides li").not('#movieG').magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true,
                    tCounter: '%curr% z %total%' // Markup for "1 of 7" counter
                },

                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function (item) {
                        return item.el.attr('title');
                    }
                }
            });

            $('#slider_mobile .slides li').magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true,
                    tCounter: '%curr% z %total%' // Markup for "1 of 7" counter
                },

                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function (item) {
                        return item.el.attr('title');
                    }
                }
            });
        }

        function initFilm() {
            $("#movie-gallery_wrapper").css('position', 'static');
        }

        return {
            init: initGallery
        }

    }();

    galleryItaka.init();
});