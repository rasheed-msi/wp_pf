(function($) {
  $(document).ready(function() {
<<<<<<< HEAD
    $('body').on('mouseover', '.mepr-tooltip', function() {
=======
    $('body').on('click', '.mepr-tooltip', function() {
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      var tooltip_title = $(this).find('.mepr-data-title').html();
      var tooltip_info = $(this).find('.mepr-data-info').html();
      $(this).pointer({ 'content':  '<h3>' + tooltip_title + '</h3><p>' + tooltip_info + '</p>',
                        'position': {'edge':'left','align':'center'},
<<<<<<< HEAD
                        'buttons': function() {
                          // intentionally left blank to eliminate 'dismiss' button
                        }
=======
                        //'buttons': function() {
                        //  // intentionally left blank to eliminate 'dismiss' button
                        //}
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
                      })
      .pointer('open');
    });

<<<<<<< HEAD
    $('body').on('mouseout', '.mepr-tooltip', function() {
      $(this).pointer('close');
    });
=======
    //$('body').on('mouseout', '.mepr-tooltip', function() {
    //  $(this).pointer('close');
    //});
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a

    if( MeprTooltip.show_about_notice ) {
      var mepr_about_pointer_id = 'mepr-about-info';

      var mepr_setup_about_pointer = function() {
        $('#'+mepr_about_pointer_id).pointer({
          content: MeprTooltip.about_notice,
          position: {'edge':'bottom','align':'left'},
          close: function() {
            var args = { action: 'mepr_close_about_notice' };
            $.post( ajaxurl, args );
          }
        }).pointer('open');
      };

      $('.toplevel_page_memberpress .wp-menu-name').attr( 'id', mepr_about_pointer_id );
      mepr_setup_about_pointer();
    }
  });
})(jQuery);
