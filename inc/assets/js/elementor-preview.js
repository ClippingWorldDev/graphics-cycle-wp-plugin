(function ($) {
  "use strict";

  var didBindElementorHooks = false;
  var didBindEditorHooks = false;
  var previewSelectors = [
    ".sidebar-button",
    ".right-sidebar-button",
    ".award-section",
    ".home1-process-slider",
    ".team-card-slider",
    ".creative-team-card-slider",
    ".home1-testimonial-slider",
    ".home2-testimonial-slider",
    ".home3-testimonial-slide",
    ".home6-testimonial-slider",
    ".award-slider",
  ].join(", ");

  function toJQuery(scope) {
    if (!scope) {
      return $(document);
    }

    return scope.jquery ? scope : $(scope);
  }

  function getScopeDocument(scope) {
    var $scope = toJQuery(scope);
    var scopeNode = $scope[0];

    if (!scopeNode) {
      return document;
    }

    return scopeNode.nodeType === 9 ? scopeNode : scopeNode.ownerDocument || document;
  }

  function hasPreviewNodes(scope) {
    return toJQuery(scope).find(previewSelectors).length > 0;
  }

  function getSwiperConstructor($element) {
    var element = $element && $element[0];
    var elementWindow = element && element.ownerDocument ? element.ownerDocument.defaultView : window;

    if (elementWindow && typeof elementWindow.Swiper === "function") {
      return elementWindow.Swiper;
    }

    if (typeof window.Swiper === "function") {
      return window.Swiper;
    }

    return null;
  }

  function createSwiper($element, options) {
    var SwiperConstructor = getSwiperConstructor($element);

    if (!SwiperConstructor || !$element.length || $element.hasClass("swiper-initialized")) {
      return null;
    }

    return new SwiperConstructor($element[0], options);
  }

  function findScopedElement($element, targetSelector, containerSelectors) {
    var selectors = containerSelectors || [];
    var i;
    var $container;
    var $target;

    for (i = 0; i < selectors.length; i += 1) {
      $container = $element.closest(selectors[i]);

      if ($container.length) {
        $target = $container.find(targetSelector).not($element).first();

        if ($target.length) {
          return $target[0];
        }
      }
    }

    $target = $element.siblings(targetSelector).first();
    if ($target.length) {
      return $target[0];
    }

    $target = $element.parent().find(targetSelector).not($element).first();
    if ($target.length) {
      return $target[0];
    }

    $target = $element.closest(".elementor-widget-container, .elementor-element, body").find(targetSelector).not($element).first();
    return $target.length ? $target[0] : null;
  }

  function getClickablePagination($element, selector, containerSelectors) {
    var paginationElement = findScopedElement($element, selector, containerSelectors);

    return paginationElement ? {
      el: paginationElement,
      clickable: true,
    } : null;
  }

  function getFractionPagination($element, selector, containerSelectors) {
    var paginationElement = findScopedElement($element, selector, containerSelectors);

    return paginationElement ? {
      el: paginationElement,
      type: "fraction",
    } : null;
  }

  function getNavigation($element, nextSelector, prevSelector, containerSelectors) {
    var nextElement = findScopedElement($element, nextSelector, containerSelectors);
    var prevElement = findScopedElement($element, prevSelector, containerSelectors);

    return nextElement && prevElement ? {
      nextEl: nextElement,
      prevEl: prevElement,
    } : null;
  }

  function initHeaderInteractions(scope) {
    var scopeDocument = getScopeDocument(scope);
    var $doc = $(scopeDocument);

    $doc
      .off("click.softroPreviewHeader", ".sidebar-button")
      .on("click.softroPreviewHeader", ".sidebar-button", function () {
        var $scopeDoc = $(this.ownerDocument);

        $(this).toggleClass("active");
        $scopeDoc.find(".main-menu").toggleClass("show-menu");
      });

    $doc
      .off("click.softroPreviewHeader", ".menu-close-btn")
      .on("click.softroPreviewHeader", ".menu-close-btn", function () {
        $(this.ownerDocument).find(".main-menu").removeClass("show-menu");
      });

    $doc
      .off("click.softroPreviewHeader", ".right-sidebar-button")
      .on("click.softroPreviewHeader", ".right-sidebar-button", function () {
        $(this.ownerDocument).find(".right-sidebar-menu").addClass("show-right-menu");
      });

    $doc
      .off("click.softroPreviewHeader", ".right-sidebar-close-btn")
      .on("click.softroPreviewHeader", ".right-sidebar-close-btn", function () {
        $(this.ownerDocument).find(".right-sidebar-menu").removeClass("show-right-menu");
      });

    $doc
      .off("click.softroPreviewHeader", ".dropdown-icon")
      .on("click.softroPreviewHeader", ".dropdown-icon", function () {
        $(this).toggleClass("active").next("ul, .mega-menu, .mega-menu2").slideToggle();
        $(this).parent().siblings().children("ul, .mega-menu, .mega-menu2").slideUp();
        $(this).parent().siblings().children(".active").removeClass("active");
      });

    $doc
      .off("click.softroPreviewHeader", ".dropdown-icon2")
      .on("click.softroPreviewHeader", ".dropdown-icon2", function () {
        $(this).toggleClass("active").next(".submenu-list").slideToggle();
        $(this).parent().siblings().children(".submenu-list").slideUp();
        $(this).parent().siblings().children(".active").removeClass("active");
      });

    $doc
      .off("click.softroPreviewHeader", ".portfolio-drop-down")
      .on("click.softroPreviewHeader", ".portfolio-drop-down", function (event) {
        var $submenu = $(this).siblings(".sub-menu");

        event.preventDefault();

        if ($submenu.length) {
          $(this).parent().siblings().find(".sub-menu").slideUp();
          $submenu.stop(true, true).slideToggle();
        }
      });

    $doc
      .off("click.softroPreviewHeader", ".language-btn")
      .on("click.softroPreviewHeader", ".language-btn", function (event) {
        $(this).parent().find(".language-list").toggleClass("active");
        event.stopPropagation();
      });

    $doc
      .off("click.softroPreviewHeaderLanguage")
      .on("click.softroPreviewHeaderLanguage", function (event) {
        if (!$(event.target).closest(".language-btn").length) {
          $(scopeDocument).find(".language-list").removeClass("active");
        }
      });
  }

  function initAwardHover(scope) {
    toJQuery(scope)
      .find(".award-section")
      .each(function () {
        var $section = $(this);

        $section
          .find(".award-list ul li")
          .off("mouseenter.softroPreviewAward")
          .on("mouseenter.softroPreviewAward", function () {
            var index = $(this).index();

            $section.find(".award-list ul li").removeClass("active");
            $(this).addClass("active");

            $section.find(".award-img ul li").removeClass("active");
            $section.find(".award-img").each(function () {
              $(this).find("ul li:eq(" + index + ")").addClass("active");
            });
          });
      });
  }

  function initSwipers(scope) {
    toJQuery(scope)
      .find(".home1-process-slider")
      .each(function () {
        var $slider = $(this);
        var navigation = getNavigation($slider, ".process-slider-next", ".process-slider-prev", [
          ".process-slider-area",
          ".home2-process-section",
          ".sass-process-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 60,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
          breakpoints: {
            280: { slidesPerView: 1, spaceBetween: 15 },
            386: { slidesPerView: 1, spaceBetween: 15 },
            576: { slidesPerView: 1, spaceBetween: 15 },
            768: { slidesPerView: 2, spaceBetween: 15 },
            992: { slidesPerView: 3, spaceBetween: 15 },
            1200: { slidesPerView: 3, spaceBetween: 25 },
            1400: { slidesPerView: 3 },
          },
        };

        if (navigation) {
          options.navigation = navigation;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".team-card-slider")
      .each(function () {
        var $slider = $(this);
        var pagination = getClickablePagination($slider, ".team-section-pagi", [
          ".row",
          ".team-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 60,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
          breakpoints: {
            280: { slidesPerView: 1, spaceBetween: 15 },
            386: { slidesPerView: 1, spaceBetween: 15 },
            576: { slidesPerView: 2, spaceBetween: 15 },
            768: { slidesPerView: 2, spaceBetween: 15 },
            992: { slidesPerView: 3, spaceBetween: 15 },
            1200: { slidesPerView: 4, spaceBetween: 15 },
            1400: { slidesPerView: 4, spaceBetween: 25 },
          },
        };

        if (pagination) {
          options.pagination = pagination;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".creative-team-card-slider")
      .each(function () {
        var $slider = $(this);
        var pagination = getClickablePagination($slider, ".creative-team-pagi", [
          ".row",
          ".team-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 60,
          autoplay: {
            delay: 2000,
            disableOnInteraction: false,
          },
          breakpoints: {
            280: { slidesPerView: 1, spaceBetween: 15 },
            386: { slidesPerView: 1, spaceBetween: 15 },
            576: { slidesPerView: 2, spaceBetween: 15 },
            768: { slidesPerView: 2, spaceBetween: 15 },
            992: { slidesPerView: 3, spaceBetween: 15 },
            1200: { slidesPerView: 4, spaceBetween: 15 },
            1400: { slidesPerView: 4, spaceBetween: 25 },
          },
        };

        if (pagination) {
          options.pagination = pagination;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".home1-testimonial-slider")
      .each(function () {
        var $slider = $(this);
        var pagination = getClickablePagination($slider, ".testimonial-pagi", [
          ".testimonial-slider-area",
          ".home1-testimonial-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 30,
          loop: true,
          effect: "fade",
          fadeEffect: {
            crossFade: true,
          },
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
        };

        if (pagination) {
          options.pagination = pagination;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".home2-testimonial-slider")
      .each(function () {
        var $slider = $(this);
        var navigation = getNavigation($slider, ".testimonial-slider-next", ".testimonial-slider-prev", [
          ".testimonial-slider-area",
          ".home2-testimonial-section",
          ".elementor-element",
        ]);
        var pagination = getFractionPagination($slider, ".franctional-pagi", [
          ".testimonial-slider-area",
          ".home2-testimonial-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 30,
          loop: true,
          effect: "fade",
          fadeEffect: {
            crossFade: true,
          },
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
        };

        if (navigation) {
          options.navigation = navigation;
        }

        if (pagination) {
          options.pagination = pagination;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".home3-testimonial-slide")
      .each(function () {
        var $slider = $(this);
        var pagination = getClickablePagination($slider, ".testimonial-section-pagi", [
          ".testimonial-slider-area",
          ".home3-testimonial-section",
          ".home4-testimonial-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 60,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
          breakpoints: {
            280: { slidesPerView: 1, spaceBetween: 15 },
            386: { slidesPerView: 1, spaceBetween: 15 },
            576: { slidesPerView: 1, spaceBetween: 15 },
            768: { slidesPerView: 1, spaceBetween: 15 },
            992: { slidesPerView: 2, spaceBetween: 15 },
            1200: { slidesPerView: 2, spaceBetween: 25 },
            1400: { slidesPerView: 2, spaceBetween: 25 },
          },
        };

        if (pagination) {
          options.pagination = pagination;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".home6-testimonial-slider")
      .each(function () {
        var $slider = $(this);
        var navigation = getNavigation($slider, ".testimonial-slider-next", ".testimonial-slider-prev", [
          ".testimonial-slider-area",
          ".home5-testimonial-section",
          ".elementor-element",
        ]);
        var options = {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 24,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          },
          breakpoints: {
            280: { slidesPerView: 1 },
            386: { slidesPerView: 1 },
            576: { slidesPerView: 1, spaceBetween: 15 },
            768: { slidesPerView: 1, spaceBetween: 15 },
            992: { slidesPerView: 2 },
            1200: { slidesPerView: 2 },
            1400: { slidesPerView: 2 },
          },
        };

        if (navigation) {
          options.navigation = navigation;
        }

        createSwiper($slider, options);
      });

    toJQuery(scope)
      .find(".award-slider")
      .each(function () {
        createSwiper($(this), {
          slidesPerView: 1,
          speed: 1500,
          spaceBetween: 60,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
          },
          breakpoints: {
            280: { slidesPerView: 1.5, spaceBetween: 15 },
            386: { slidesPerView: 1.5, spaceBetween: 15 },
            576: { slidesPerView: 2.5, spaceBetween: 15 },
            768: { slidesPerView: 3.5, spaceBetween: 15 },
            992: { slidesPerView: 4.5, spaceBetween: 15 },
            1200: { slidesPerView: 5.5, spaceBetween: 25 },
            1400: { slidesPerView: 6.5, spaceBetween: 25 },
          },
        });
      });
  }

  function mount(scope) {
    if (!hasPreviewNodes(scope)) {
      return;
    }

    initHeaderInteractions(scope);
    initAwardHover(scope);
    initSwipers(scope);
  }

  function bindElementorHooks() {
    if (didBindElementorHooks || !window.elementorFrontend || !window.elementorFrontend.hooks) {
      return;
    }

    didBindElementorHooks = true;

    window.elementorFrontend.hooks.addAction("frontend/element_ready/global", function ($scope) {
      mount($scope);
    });
  }

  function bindEditorHooks() {
    if (didBindEditorHooks || !window.elementor || typeof window.elementor.on !== "function") {
      return;
    }

    didBindEditorHooks = true;

    window.elementor.on("preview:loaded", function () {
      if (window.elementor.$previewContents && window.elementor.$previewContents.length) {
        mount(window.elementor.$previewContents);
      }
    });
  }

  function bootstrap() {
    bindElementorHooks();
    bindEditorHooks();

    if (hasPreviewNodes(document)) {
      mount(document);
    }

    if (window.elementor && window.elementor.$previewContents && window.elementor.$previewContents.length) {
      mount(window.elementor.$previewContents);
    }
  }

  $(function () {
    bootstrap();
  });

  $(window).on("elementor/frontend/init", function () {
    bootstrap();
  });

  if ((window.elementorFrontend && window.elementorFrontend.hooks) || (window.elementor && window.elementor.$previewContents)) {
    bootstrap();
  }
})(jQuery);
