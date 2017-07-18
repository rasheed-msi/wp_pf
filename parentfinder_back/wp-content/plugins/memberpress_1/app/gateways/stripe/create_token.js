(function ($) {
  // this identifies your website in the createToken call below
  Stripe.setPublishableKey(MeprStripeGateway.public_key);

  $(document).ready(function() {
<<<<<<< HEAD
    $("#payment-form").submit(function(e) {
      e.preventDefault();
      // disable the submit button to prevent repeated clicks
      //$('.submit-button').attr("disabled", "disabled");
      $('.stripe-loading-gif').show();

      var exp = $('.cc-exp').payment('cardExpiryVal');

      var tok_args = {
        name: $('.card-name').val(),
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
=======
    $('body').on('mepr-checkout-submit', function(e, payment_form) {
      e.preventDefault();

      var exp = $(payment_form).find('.cc-exp').payment('cardExpiryVal');

      var tok_args = {
        name: $(payment_form).find('.card-name').val(),
        number: $(payment_form).find('.card-number').val(),
        cvc: $(payment_form).find('.card-cvc').val(),
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
        exp_month: exp.month,
        exp_year: exp.year
      };

      // Send address if it's there
<<<<<<< HEAD
      if( $('.card-address-1').length != 0 ) { tok_args['address_line1'] = $('.card-address-1').val(); }
      if( $('.card-address-2').length != 0 ) { tok_args['address_line2'] = $('.card-address-2').val(); }
      if( $('.card-city').length != 0 ) { tok_args['address_city'] = $('.card-city').val(); }
      if( $('.card-state').length != 0 ) { tok_args['address_state'] = $('.card-state').val(); }
      if( $('.card-zip').length != 0 ) { tok_args['address_zip'] = $('.card-zip').val(); }
      if( $('.card-country').length != 0 ) { tok_args['address_country'] = $('.card-country').val(); }
=======
      if( $(payment_form).find('.card-address-1').length != 0 ) { tok_args['address_line1'] = $(payment_form).find('.card-address-1').val(); }
      if( $(payment_form).find('.card-address-2').length != 0 ) { tok_args['address_line2'] = $(payment_form).find('.card-address-2').val(); }
      if( $(payment_form).find('.card-city').length != 0 ) { tok_args['address_city'] = $(payment_form).find('.card-city').val(); }
      if( $(payment_form).find('.card-state').length != 0 ) { tok_args['address_state'] = $(payment_form).find('.card-state').val(); }
      if( $(payment_form).find('.card-zip').length != 0 ) { tok_args['address_zip'] = $(payment_form).find('.card-zip').val(); }
      if( $(payment_form).find('.card-country').length != 0 ) { tok_args['address_country'] = $(payment_form).find('.card-country').val(); }
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a

      // createToken returns immediately - the supplied callback submits the form if there are no errors
      Stripe.createToken( tok_args, function(status, response) {
        //console.info('message', response);
        if(response.error) {
          // re-enable the submit button
<<<<<<< HEAD
          $('.mepr-submit').removeAttr("disabled");
          // show the errors on the form
          $('.mepr-stripe-errors').html(response.error.message);
          $('.mepr-stripe-errors').addClass('mepr_error');
          // hide the spinning gif bro
          $('.mepr-loading-gif').hide();
        } else {
          $('.mepr-stripe-errors').removeClass('mepr_error');
          var form$ = $("#payment-form");
          // token contains id, last4, and card type
          var token = response['id'];
          // insert the token into the form so it gets submitted to the server
          form$.append("<input type='hidden' name='stripe_token' value='" + token + "' />");
          // and submit
          form$.get(0).submit();
        }
      });
=======
          $(payment_form).find('.mepr-submit').disabled = false;
          // show the errors on the form
          $(payment_form).find('.mepr-stripe-errors').html(response.error.message);
          $(payment_form).find('.mepr-stripe-errors').addClass('mepr_error');
          // hide the spinning gif bro
          $(payment_form).find('.mepr-loading-gif').hide();
        } else {
          $(payment_form).find('.mepr-stripe-errors').removeClass('mepr_error');
          // token contains id, last4, and card type
          var token = response['id'];

          // Don't do anything if there's already a token, if it is
          // present chances are the form has already been submitted
          if(!$(payment_form).hasClass('mepr-payment-submitted')) {
            $(payment_form).addClass('mepr-payment-submitted');

            // insert the token into the form so it gets submitted to the server
            payment_form.append('<input type="hidden" class="mepr-stripe-token" name="stripe_token" value="' + token + '" />');

            // and submit
            payment_form.get(0).submit();
          }
        }
      });

>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      return false; // submit from callback
    });
  });
})(jQuery);
<<<<<<< HEAD
=======

>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
