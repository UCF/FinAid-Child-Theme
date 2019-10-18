(($) => {

  $.fn.smoothScrollAnchor = function () {
    /**
     * The primary each loop for the jQuery objects
     **/
    return this.each(function () {
      const $link = $(this);
      const $anchor = $($link.attr('href'));

      /**
       * The on click event for the link
       **/
      if ($anchor.length) {
        $link.on('click', (e) => {
          e.preventDefault();
          $([document.documentElement, document.body]).animate({
            scrollTop: $anchor.offset().top
          }, 500);
        });
      }
    });
  };

  $(document).ready(() => {
    $('.js-smoothscroll').smoothScrollAnchor();
  });

})(jQuery);
