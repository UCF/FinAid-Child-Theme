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
        $icon.toggleClass(`${closed} ${opened}`);
      });
    });
  };

  $(document).ready(() => {
    $('a[data-toggle="collapse"]').collapseIcon();
  });

})(jQuery);
