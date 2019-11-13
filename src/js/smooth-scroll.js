(($) => {

  $.fn.smoothScrollAnchor = function () {
    /**
     * The primary each loop for the jQuery objects
     **/
    return this.each(function () {
      const $link = $(this);
      const $anchor = $($link.attr('href'));
      const pageTitle = document.title;

      /**
       * The on click event for the link
       **/
      if ($anchor.length) {
        $link.on('click', (e) => {
          e.preventDefault();

          const newTitle = `${pageTitle} | ${$anchor[0].innerText}`;
          const href = $link.attr('href');

          history.pushState({
            id: $anchor[0].id
          }, newTitle, href);

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
