(($) => {

  $.fn.collapseIcon = function () {
    /**
     * The primary each loop for the jQuery objects
     **/
    return this.each((i, o) => {
      const $object = $(o);
      const closed  = 'fa-plus-circle';
      const opened  = 'fa-minus-circle';

      $object.click(() => {
        const $icon = $object.find('.fa');

        if ($icon.hasClass(closed)) {
          $icon.removeClass(closed);
          $icon.addClass(opened);
        } else if ($icon.hasClass(opened)) {
          $icon.removeClass(opened);
          $icon.addClass(closed);
        }
      });
    });
  };

  $(document).ready(() => {
    $('a[data-toggle="collapse"]').collapseIcon();
  });

})(jQuery);
