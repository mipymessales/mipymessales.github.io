
  (function ($) {
  
  "use strict";

    // MENU
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });
    
    // CUSTOM LINK
    $('.smoothscroll').click(function(){
      var el = $(this).attr('href');
      var elWrapped = $(el);
      var header_height = $('.navbar').height();
  
      scrollToDiv(elWrapped,header_height);
      return false;
  
      function scrollToDiv(element,navheight){
        var offset = element.offset();
        var offsetTop = offset.top;
        var totalScroll = offsetTop-navheight;
  
        $('body,html').animate({
        scrollTop: totalScroll
        }, 300);
      }
    });

    $(window).on('scroll', function(){
      function isScrollIntoView(elem, index) {
        var $elem = $(elem);
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();
        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $(window).height() * 0.5;
        var $h4 = $elem.find('h4');

        if (elemBottom <= docViewBottom && elemTop >= docViewTop) {
          $elem.addClass('active');
          $h4.css({
            'color': '#212529',
            'letter-spacing': '0.3rem'
          });
        } else {
          $elem.removeClass('active');
          $h4.css({
            'color': 'var(--dark-color)',
            'letter-spacing': '0'
          });
        }

        var MainTimelineContainer = $('#vertical-scrollable-timeline')[0];
        var MainTimelineContainerBottom = MainTimelineContainer.getBoundingClientRect().bottom - $(window).height() * 0.5;
        $(MainTimelineContainer).find('.inner').css('height', MainTimelineContainerBottom + 'px');
      }

      var timeline = $('#vertical-scrollable-timeline li');
      Array.from(timeline).forEach(isScrollIntoView);

    });
  
  })(window.jQuery);


